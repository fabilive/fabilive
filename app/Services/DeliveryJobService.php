<?php

namespace App\Services;

use App\Models\DeliveryJob;
use App\Models\DeliveryJobEvent;
use App\Models\Order;
use App\Models\Rider;
use App\Models\Transaction;
use App\Models\User;
use App\Models\VendorOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DeliveryJobService
{
    protected $feeService;

    protected $routeService;

    public function __construct(DeliveryFeeService $feeService, DeliveryRouteService $routeService)
    {
        $this->feeService = $feeService;
        $this->routeService = $routeService;
    }

    /**
     * Create a delivery job from an order.
     */
    public function createJobFromOrder(Order $order): DeliveryJob
    {
        return DB::transaction(function () use ($order) {
            // 1. Identify unique sellers
            $vendorOrders = VendorOrder::where('order_id', $order->id)->get();
            $sellersCount = $vendorOrders->pluck('user_id')->unique()->count();

            // 2. Calculate fees
            $fees = $this->feeService->calculateFee($sellersCount);

            // 3. Create Job
            $job = DeliveryJob::create([
                'order_id' => $order->id,
                'buyer_id' => $order->user_id,
                'status' => 'pending_readiness',
                'service_area_id' => $order->service_area_id,
                'base_fee' => $fees['base_fee'],
                'stopover_fee' => $fees['stopover_fee'],
                'sellers_count' => $sellersCount,
                'delivery_fee_total' => $fees['total'],
                'platform_delivery_commission' => $fees['platform_commission'],
                'rider_earnings' => $fees['rider_earnings'],
            ]);

            // 4. Create Pickup Stops
            $stopsData = [];
            foreach ($vendorOrders->unique('user_id') as $vo) {
                $seller = User::find($vo->user_id);
                $stopsData[] = [
                    'type' => 'pickup',
                    'seller_id' => $seller->id,
                    'location_text' => $seller->address,
                    'lat' => $seller->lat,
                    'lng' => $seller->lng,
                    'status' => 'pending',
                ];
            }

            // 5. Optimize Stops Sequence
            $optimizedStops = $this->routeService->optimizePickupSequence(
                $stopsData,
                (float) $order->customer_lat,
                (float) $order->customer_lng
            );

            // 6. Save Stops
            foreach ($optimizedStops as $stopData) {
                $job->stops()->create($stopData);
            }

            // 7. Create Dropoff Stop
            $job->stops()->create([
                'type' => 'dropoff',
                'sequence' => $sellersCount + 1,
                'status' => 'pending',
                'location_text' => $order->customer_address,
                'lat' => $order->customer_lat,
                'lng' => $order->customer_lng,
            ]);

            // 8. Log Event
            $this->logEvent($job, 'system', null, 'job_created', ['sellers_count' => $sellersCount]);

            return $job;
        });
    }

    /**
     * Transition job status.
     */
    public function transitionStatus(DeliveryJob $job, string $newStatus, string $actorType, ?int $actorId, array $meta = []): void
    {
        DB::transaction(function () use ($job, $newStatus, $actorType, $actorId, $meta) {
            $oldStatus = $job->status;
            $job->update(['status' => $newStatus]);

            $this->logEvent($job, $actorType, $actorId, 'status_changed', array_merge([
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ], $meta));

            // Status specific updates
            if ($newStatus === 'assigned') {
                $job->update(['accepted_at' => now()]);

                // Notify buyer + rider + seller of rider assignment
                try {
                    $order = $job->order;
                    if ($order) {
                        \App\Services\FabiliveNotifier::riderAssigned($order, $job->assigned_rider_id);
                    }
                } catch (\Exception $ne) {
                    \Log::error('Rider Assignment Notification Error: ' . $ne->getMessage());
                }
            } elseif ($newStatus === 'picked_up') {
                $job->update(['picked_up_at' => now()]);
            } elseif ($newStatus === 'delivered' || $newStatus === 'delivered_pending_verification') {
                $job->update(['delivered_at' => now()]);
            } elseif ($newStatus === 'delivered_verified') {
                $job->update(['delivered_at' => $job->delivered_at ?? now()]);
            } elseif ($newStatus === 'cancelled') {
                $job->update(['cancelled_at' => now()]);
            } elseif ($newStatus === 'returned') {
                $job->update(['returned_at' => now()]);
            }
        });
    }

    public function completeDeliveryJob(DeliveryJob $job, string $proofPhotoPath = null): void
    {
        DB::transaction(function () use ($job, $proofPhotoPath) {
            // 1. Update Job Status & Metadata
            $job->update([
                'status' => 'delivered_pending_verification',
                'delivered_at' => now(),
                'proof_photo' => $proofPhotoPath,
                'proof_uploaded_at' => $proofPhotoPath ? now() : null,
            ]);

            // 2. Update Order Status
            $order = $job->order;
            $updateData = ['status' => 'delivered'];
            if ($order->method === 'Cash On Delivery') {
                $updateData['payment_status'] = 'Completed';
            }
            $order->update($updateData);

            // 3. Log Event
            $this->logEvent($job, 'rider', $job->assigned_rider_id, 'delivered_pending_verification');

            // 4. Send delivery notifications (buyer + seller + rider)
            try {
                \App\Services\FabiliveNotifier::orderDelivered($order, $job->assigned_rider_id);
            } catch (\Exception $ne) {
                \Log::error('Delivery Notification Error: ' . $ne->getMessage());
            }

            // 5. Close Chat Threads immediately upon delivery
            app(DeliveryChatService::class)->closeChatThreads($job);
        });
    }

    /**
     * Admin verifies proof and settles all parties.
     */
    public function verifyAndSettle(DeliveryJob $job): void
    {
        DB::transaction(function () use ($job) {
            if ($job->status === 'delivered_verified') {
                return;
            }

            $order = $job->order;

            // 1. Update Job Status
            $job->update([
                'status' => 'delivered_verified',
                'verified_at' => now(),
            ]);

            // 2. Update Order Status to Fully Completed
            $order->update(['status' => 'completed']);

            // 3. Update Vendor Orders Status
            VendorOrder::where('order_id', $order->id)->update(['status' => 'completed']);

            // 4. Financial Settlement: Credit Rider
            if ($job->assigned_rider_id) {
                $rider = Rider::lockForUpdate()->find($job->assigned_rider_id);
                if ($rider) {
                    $rider->increment('balance', (float) $job->rider_earnings);

                    // Create Transaction for Rider
                    $transaction = new Transaction;
                    $transaction->txn_number = Str::random(3).substr(time(), 6, 8).Str::random(3);
                    $transaction->user_id = $rider->id;
                    $transaction->amount = (float) $job->rider_earnings;
                    $transaction->currency_sign = $order->currency_sign;
                    $transaction->currency_code = $order->currency_code;
                    $transaction->currency_value = $order->currency_value;
                    $transaction->method = 'Delivery Earning';
                    $transaction->type = 'plus';
                    $transaction->details = 'Delivery Earning for Order #'.$order->order_number;
                    $transaction->save();
                }
            }

            // 5. Vendor Settlement (Release Escrow)
            foreach ($order->vendororders as $vorder) {
                $vendor = User::lockForUpdate()->find($vorder->user_id);
                if ($vendor) {
                    // Logic from Admin\OrderController: price - commission
                    $settlementAmount = $vorder->price - $order->commission;
                    $vendor->increment('current_balance', $settlementAmount);

                    // Log to WalletLedger
                    \App\Models\WalletLedger::create([
                        'user_id' => $vendor->id,
                        'amount' => $settlementAmount,
                        'type' => 'escrow_release',
                        'order_id' => $order->id,
                        'reference' => $order->txnid,
                        'status' => 'completed',
                        'details' => 'Escrow released upon delivery verification',
                    ]);
                }
            }

            // 6. Release Main Order Escrow Status
            $order->update(['escrow_status' => 'released']);

            // 7. Log Events
            $this->logEvent($job, 'admin', Auth::id(), 'job_verified_and_settled');

            // 8. Close Chat Threads (Force close now)
            app(DeliveryChatService::class)->closeChatThreads($job);
        });
    }

    /**
     * Log a delivery event.
     */
    public function logEvent(DeliveryJob $job, string $actorType, ?int $actorId, string $event, array $meta = []): void
    {
        DeliveryJobEvent::create([
            'delivery_job_id' => $job->id,
            'actor_type' => $actorType,
            'actor_id' => $actorId,
            'event' => $event,
            'meta_json' => $meta,
        ]);
    }
}

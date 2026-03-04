<?php

namespace App\Services;

use App\Models\DeliveryJob;
use App\Models\DeliveryJobStop;
use App\Models\DeliveryJobEvent;
use App\Models\Order;
use App\Models\User;
use App\Models\VendorOrder;
use Illuminate\Support\Facades\DB;

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
                    'status' => 'pending'
                ];
            }

            // 5. Optimize Stops Sequence
            $optimizedStops = $this->routeService->optimizePickupSequence(
                $stopsData, 
                (float)$order->customer_lat, 
                (float)$order->customer_lng
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
                'lng' => $order->customer_lng
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
                'new_status' => $newStatus
            ], $meta));

            // Status specific updates
            if ($newStatus === 'assigned') {
                $job->update(['accepted_at' => now()]);
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
            'meta_json' => $meta
        ]);
    }
}

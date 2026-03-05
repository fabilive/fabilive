<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Generalsetting;
use App\Models\WalletLedger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class EscrowReleaseService
{
    /**
     * Releases the escrow funds for an order to the Seller and the Rider, minus Admin commissions.
     * Must be called only when an order is successfully Delivered and Admin verified.
     *
     * @param Order $order
     * @return bool
     * @throws Exception
     */
    public function releaseOrderEscrow(Order $order): bool
    {
        if (strtolower($order->status) !== 'completed' && strtolower($order->status) !== 'delivered') {
            throw new Exception("Order is not in a completed or delivered state. Current status: {$order->status}");
        }

        if (strtolower($order->escrow_status) === 'released') {
            throw new Exception("Escrow for this order has already been released.");
        }

        if (boolval($order->admin_verified) !== true) {
            throw new Exception("Escrow release requires explicit Admin verification for order #{$order->order_number}");
        }

        $gs = Generalsetting::first();

        try {
            return DB::transaction(function () use ($order, $gs) {

            $sellerAmount = $order->pay_amount; // Contains product cost.
            $riderFee = $order->total_delivery_fee ?? 0;

            // 1. Calculate Seller Commission (Admin take)
            $adminSellerCommission = 0;
            if ($gs->percentage_commission > 0) {
                $adminSellerCommission += ($sellerAmount * $gs->percentage_commission) / 100;
            }
            if ($gs->fixed_commission > 0) {
                $adminSellerCommission += $gs->fixed_commission;
            }
            $finalSellerPayout = max(0, $sellerAmount - $adminSellerCommission);

            // 2. Calculate Rider Commission (Admin take)
            $adminRiderCommission = 0;
            if ($gs->rider_percentage_commission > 0) {
                $adminRiderCommission = ($riderFee * $gs->rider_percentage_commission) / 100;
            }
            $finalRiderPayout = max(0, $riderFee - $adminRiderCommission);

            // 3. Release Seller Funds via vendororders
            if ($order->vendororders->count() > 0) {
                foreach ($order->vendororders as $vOrder) {
                    $vendorId = $vOrder->user_id;
                    if ($vendorId && $vendorId > 0) { // Not in-house (admin)
                        // Lock vendor for update
                        $vendor = User::lockForUpdate()->find($vendorId);
                        if ($vendor) {
                            $vendorAmount = $vOrder->price;
                            
                            $adminSellerCommission = 0;
                            if ($gs->percentage_commission > 0) {
                                $adminSellerCommission += ($vendorAmount * $gs->percentage_commission) / 100;
                            }
                            if ($gs->fixed_commission > 0) {
                                // Assuming fixed commission applies per vendor order
                                $adminSellerCommission += $gs->fixed_commission;
                            }
                            
                            $finalSellerPayout = max(0, $vendorAmount - $adminSellerCommission);

                            $vendor->balance += $finalSellerPayout;
                            $vendor->save();

                            WalletLedger::create([
                                'user_id' => $vendor->id,
                                'amount' => $finalSellerPayout,
                                'type' => 'escrow_release',
                                'order_id' => $order->id,
                                'reference' => $order->order_number,
                                'status' => 'completed',
                                'details' => 'Escrow released for order #' . $order->order_number
                            ]);
                        }
                    }
                }
            } else {
                // If no vendor orders exist, it might be purely an in-house order with no separate vendor.
                // We assume admin gets the full amount, so no seller wallet ledger needed.
            }

            // 4. Release Rider Funds
            $riderAssignedId = $order->deliveryRider->rider_id ?? null;
            if ($riderAssignedId) {
                // Lock rider for update
                $rider = User::lockForUpdate()->find($riderAssignedId);
                if ($rider) {
                    $rider->balance += $finalRiderPayout;
                    $rider->save();

                    WalletLedger::create([
                        'user_id' => $rider->id,
                        'amount' => $finalRiderPayout,
                        'type' => 'escrow_release',
                        'order_id' => $order->id,
                        'reference' => 'D-'.$order->order_number,
                        'status' => 'completed',
                        'details' => 'Delivery fee escrow released for order #' . $order->order_number
                    ]);
                }
            }

            if ($order->payout_released_at) {
                throw new Exception("Payout for this order has already been processed.");
            }

            // 5. Update Order Escrow Status
            $order->escrow_status = 'released';
            $order->payout_released_at = now();
            $order->save();

            return true;

            }, 5);

        } catch (Exception $e) {
            Log::error('Escrow Release Error for Order ' . $order->id . ': ' . $e->getMessage());
            throw $e;
        }
    }
}

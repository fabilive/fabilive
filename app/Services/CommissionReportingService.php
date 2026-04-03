<?php

namespace App\Services;

use App\Models\DeliveryJob;
use App\Models\Order;
use App\Models\WalletLedger;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CommissionReportingService
{
    /**
     * Get summary of commissions and fees for a date range.
     */
    public function getCommissionSummary($startDate = null, $endDate = null)
    {
        $query = WalletLedger::where('type', 'commission');

        if ($startDate) {
            $query->where('created_at', '>=', Carbon::parse($startDate)->startOfDay());
        }
        if ($endDate) {
            $query->where('created_at', '<=', Carbon::parse($endDate)->endOfDay());
        }

        return [
            'total_admin_commission' => $query->sum('amount'),
            'total_delivery_fees' => DeliveryJob::whereIn('status', ['delivered_verified'])
                ->whereBetween('created_at', [
                    Carbon::parse($startDate ?? '1970-01-01')->startOfDay(),
                    Carbon::parse($endDate ?? now())->endOfDay(),
                ])
                ->sum(DB::raw('base_fee + stopover_fee')),
            'transaction_count' => $query->count(),
        ];
    }

    /**
     * Get per-order breakdown for reconciliation.
     */
    public function getReconciliationData($startDate = null, $endDate = null)
    {
        return Order::with(['deliveryJob', 'walletLedgers'])
            ->where('admin_verified', true)
            ->whereBetween('updated_at', [
                Carbon::parse($startDate ?? '1970-01-01')->startOfDay(),
                Carbon::parse($endDate ?? now())->endOfDay(),
            ])
            ->get()
            ->map(function ($order) {
                $adminLedger = $order->walletLedgers->where('type', 'commission')->first();
                $riderLedger = $order->walletLedgers->where('type', 'delivery_fee')->first();

                return [
                    'order_number' => $order->order_number,
                    'gross_amount' => $order->pay_amount,
                    'admin_commission' => $adminLedger ? $adminLedger->amount : 0,
                    'rider_fee' => $riderLedger ? abs($riderLedger->amount) : 0,
                    'delivery_job_status' => $order->deliveryJob ? $order->deliveryJob->status : 'N/A',
                    'date' => $order->updated_at->toDateTimeString(),
                ];
            });
    }
}

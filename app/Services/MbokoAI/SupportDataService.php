<?php

namespace App\Services\MbokoAI;

use App\Models\DeliveryJob;
use App\Models\Order;
use App\Models\PayoutRequest;
use App\Models\ReferralCode;
use App\Models\Rider;
use App\Models\User;
use App\Models\VendorOrder;
use App\Models\WalletLedger;
use App\Models\Withdraw;
use Illuminate\Support\Facades\DB;

class SupportDataService
{
    /**
     * Get order by ID with ownership check.
     *
     * @return array{found: bool, order: ?Order, authorized: bool, summary: ?array}
     */
    public function getOrderById(int $orderId, int $userId, string $role): array
    {
        $order = Order::with(['deliveryJob', 'vendororders', 'tracks'])->find($orderId);

        if (!$order) {
            return ['found' => false, 'order' => null, 'authorized' => false, 'summary' => null];
        }

        // Authorization check based on role
        $authorized = false;
        switch ($role) {
            case 'buyer':
                $authorized = ($order->user_id == $userId);
                break;
            case 'vendor':
                // Seller can see orders that contain their products
                $authorized = VendorOrder::where('order_id', $orderId)
                    ->where('user_id', $userId)
                    ->exists();
                break;
            case 'rider':
                // Rider can see orders assigned to them
                $rider = Rider::find($userId);
                $authorized = $rider && DeliveryJob::where('order_id', $orderId)
                    ->where('assigned_rider_id', $rider->id)
                    ->exists();
                break;
            case 'admin':
                $authorized = true;
                break;
        }

        if (!$authorized) {
            return ['found' => true, 'order' => null, 'authorized' => false, 'summary' => null];
        }

        return [
            'found' => true,
            'order' => $order,
            'authorized' => true,
            'summary' => $this->buildOrderSummary($order, $role),
        ];
    }

    /**
     * Build a human-readable order summary based on role.
     */
    protected function buildOrderSummary(Order $order, string $role): array
    {
        $deliveryJob = $order->deliveryJob;

        $summary = [
            'order_number' => $order->order_number,
            'status' => $this->formatOrderStatus($order->status),
            'payment_status' => $order->payment_status ?? 'unknown',
            'total' => $order->currency_sign . ' ' . number_format($order->pay_amount, 0),
            'date' => $order->created_at?->format('M d, Y H:i'),
        ];

        // Delivery info
        if ($deliveryJob) {
            $summary['delivery'] = [
                'status' => $deliveryJob->status ?? 'pending',
                'rider_assigned' => !empty($deliveryJob->assigned_rider_id),
                'rider_name' => $deliveryJob->rider ? $deliveryJob->rider->name : null,
                'accepted_at' => $deliveryJob->accepted_at?->format('M d, Y H:i'),
                'picked_up_at' => $deliveryJob->picked_up_at?->format('M d, Y H:i'),
                'delivered_at' => $deliveryJob->delivered_at?->format('M d, Y H:i'),
            ];
        } else {
            $summary['delivery'] = [
                'status' => 'no_delivery_job',
                'rider_assigned' => false,
                'rider_name' => null,
            ];
        }

        // Role-specific additions
        if ($role === 'buyer') {
            $summary['customer_name'] = $order->customer_name;
        }

        if ($role === 'vendor') {
            $vendorOrders = VendorOrder::where('order_id', $order->id)->get();
            $summary['vendor_items'] = $vendorOrders->map(fn($vo) => [
                'qty' => $vo->qty,
                'price' => $vo->price,
                'status' => $vo->status,
            ])->toArray();
        }

        return $summary;
    }

    /**
     * Get rider assignment status for an order.
     * Does NOT expose rider phone for privacy.
     */
    public function getRiderForOrder(int $orderId): array
    {
        $deliveryJob = DeliveryJob::with('rider')->where('order_id', $orderId)->first();

        if (!$deliveryJob) {
            return [
                'has_delivery_job' => false,
                'rider_assigned' => false,
                'rider_name' => null,
                'delivery_status' => 'no_job_created',
            ];
        }

        return [
            'has_delivery_job' => true,
            'rider_assigned' => !empty($deliveryJob->assigned_rider_id),
            'rider_name' => $deliveryJob->rider?->name,
            'delivery_status' => $deliveryJob->status,
            'accepted_at' => $deliveryJob->accepted_at?->format('M d, Y H:i'),
            'picked_up_at' => $deliveryJob->picked_up_at?->format('M d, Y H:i'),
            'delivered_at' => $deliveryJob->delivered_at?->format('M d, Y H:i'),
        ];
    }

    /**
     * Get delivery status for an order.
     */
    public function getDeliveryStatus(int $orderId): array
    {
        $deliveryJob = DeliveryJob::with(['rider', 'stops'])->where('order_id', $orderId)->first();

        if (!$deliveryJob) {
            return ['status' => 'no_delivery_created', 'details' => 'No delivery job has been created for this order yet.'];
        }

        $statusMessages = [
            'pending' => 'The delivery is waiting to be picked up by a rider.',
            'accepted' => 'A rider has accepted the delivery and is on the way to pick up.',
            'picked_up' => 'The rider has picked up the items and is on the way to deliver.',
            'delivered' => 'The order has been delivered successfully.',
            'cancelled' => 'The delivery was cancelled.',
            'returned' => 'The order was returned.',
        ];

        return [
            'status' => $deliveryJob->status,
            'details' => $statusMessages[$deliveryJob->status] ?? 'Status: ' . $deliveryJob->status,
            'rider_name' => $deliveryJob->rider?->name,
            'stops_count' => $deliveryJob->stops->count(),
        ];
    }

    /**
     * Get payment status for an order.
     * Only returns safe info — no raw transaction IDs for non-admin roles.
     */
    public function getPaymentStatus(int $orderId, string $role = 'buyer'): array
    {
        $order = Order::find($orderId);
        if (!$order) {
            return ['found' => false, 'status' => null];
        }

        $result = [
            'found' => true,
            'status' => $order->payment_status ?? 'unknown',
            'method' => $order->method ?? 'unknown',
            'amount' => $order->currency_sign . ' ' . number_format($order->pay_amount, 0),
        ];

        // Only show transaction ID to admins and vendors
        if (in_array($role, ['admin', 'vendor'])) {
            $result['transaction_id'] = $order->txnid;
        } else {
            $result['transaction_id'] = $order->txnid ? '***' . substr($order->txnid, -4) : null;
        }

        return $result;
    }

    /**
     * Get wallet summary for a user.
     */
    public function getWalletSummary(int $userId, string $role): array
    {
        if ($role === 'rider') {
            $rider = Rider::find($userId);
            if (!$rider) {
                return ['found' => false];
            }

            $recentTransactions = DB::table('wallet_ledger')
                ->where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return [
                'found' => true,
                'balance' => $rider->balance ?? 0,
                'recent_transactions' => $recentTransactions->map(fn($t) => [
                    'amount' => $t->amount,
                    'type' => $t->type,
                    'reference' => $t->reference,
                    'date' => $t->created_at,
                ])->toArray(),
            ];
        }

        $user = User::find($userId);
        if (!$user) {
            return ['found' => false];
        }

        $recentLedger = WalletLedger::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return [
            'found' => true,
            'balance' => $user->current_balance ?? 0,
            'referral_locked' => $user->referral_locked_balance ?? 0,
            'recent_transactions' => $recentLedger->map(fn($t) => [
                'amount' => $t->amount,
                'type' => $t->type,
                'reference' => $t->reference,
                'date' => $t->created_at?->format('M d, Y'),
            ])->toArray(),
        ];
    }

    /**
     * Get withdrawal status for a user.
     */
    public function getWithdrawalStatus(int $userId, string $role): array
    {
        // Check both payout_requests and withdraws tables
        $payoutRequests = PayoutRequest::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $withdraws = Withdraw::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $combined = collect();

        foreach ($payoutRequests as $pr) {
            $combined->push([
                'source' => 'payout_request',
                'amount' => $pr->amount,
                'method' => $pr->method,
                'status' => $pr->status,
                'date' => $pr->created_at?->format('M d, Y'),
            ]);
        }

        foreach ($withdraws as $w) {
            $combined->push([
                'source' => 'withdrawal',
                'amount' => $w->amount,
                'method' => $w->method,
                'status' => $w->status,
                'date' => $w->created_at?->format('M d, Y'),
            ]);
        }

        return [
            'has_withdrawals' => $combined->isNotEmpty(),
            'recent' => $combined->sortByDesc('date')->take(5)->values()->toArray(),
        ];
    }

    /**
     * Get referral status for a user.
     */
    public function getReferralStatus(int $userId, string $role): array
    {
        if ($role === 'rider') {
            $codes = ReferralCode::where('rider_id', $userId)->get();
        } else {
            $codes = ReferralCode::where('user_id', $userId)->get();
        }

        if ($codes->isEmpty()) {
            return [
                'has_referrals' => false,
                'codes' => [],
                'total_usages' => 0,
            ];
        }

        return [
            'has_referrals' => true,
            'codes' => $codes->map(fn($c) => [
                'code' => $c->code,
                'active' => $c->isActive(),
                'usages' => $c->usages_count,
                'max_usages' => $c->max_usages,
            ])->toArray(),
            'total_usages' => $codes->sum('usages_count'),
        ];
    }

    /**
     * Get rider delivery jobs summary (for rider role).
     */
    public function getRiderJobsSummary(int $riderId): array
    {
        $rider = Rider::find($riderId);
        if (!$rider) {
            return ['found' => false];
        }

        $activeJobs = DeliveryJob::where('assigned_rider_id', $riderId)
            ->whereNotIn('status', ['delivered', 'cancelled', 'returned'])
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $totalDelivered = DeliveryJob::where('assigned_rider_id', $riderId)
            ->where('status', 'delivered')
            ->count();

        return [
            'found' => true,
            'rider_name' => $rider->name,
            'is_available' => $rider->is_available,
            'active_jobs' => $activeJobs->map(fn($j) => [
                'order_id' => $j->order_id,
                'order_number' => $j->order?->order_number,
                'status' => $j->status,
                'delivery_fee' => $j->rider_earnings,
            ])->toArray(),
            'total_delivered' => $totalDelivered,
            'balance' => $rider->balance ?? 0,
        ];
    }

    /**
     * Get seller earnings summary.
     */
    public function getSellerEarnings(int $userId): array
    {
        $user = User::find($userId);
        if (!$user || $user->is_vendor != 2) {
            return ['found' => false];
        }

        $totalSales = VendorOrder::where('user_id', $userId)->sum('price');
        $orderCount = VendorOrder::where('user_id', $userId)->count();

        return [
            'found' => true,
            'shop_name' => $user->shop_name,
            'current_balance' => $user->current_balance ?? 0,
            'total_sales' => $totalSales,
            'total_orders' => $orderCount,
        ];
    }

    /**
     * Format order status to human-readable.
     */
    protected function formatOrderStatus(string $status): string
    {
        $map = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'declined' => 'Declined',
            'on delivery' => 'On Delivery',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ];

        return $map[strtolower($status)] ?? ucfirst($status);
    }
}

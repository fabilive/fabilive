<?php

namespace App\Services;

use App\Classes\GeniusMailer;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\VendorOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FabiliveNotifier
{
    // ══════════════════════════════════════════════════════════
    //  ORDER LIFECYCLE EVENTS
    // ══════════════════════════════════════════════════════════

    /**
     * Triggered when a buyer places an order (from finalizeOrder).
     */
    public static function orderPlaced(Order $order): void
    {
        try {
            $gs = Generalsetting::safeFirst();

            // 1. Buyer email
            self::sendTemplateEmail('order_placed', $order->customer_email, [
                'cname'   => $order->customer_name,
                'onumber' => $order->order_number,
                'oamount' => ($order->currency_sign ?? 'CFA') . ' ' . number_format($order->pay_amount, 0),
            ]);

            // 2. Buyer in-app notification
            if ($order->user_id) {
                self::createNotification(
                    'user', $order->user_id,
                    'order_placed',
                    'Your order #' . $order->order_number . ' has been placed successfully!',
                    $order->order_number,
                    null,
                    'fas fa-shopping-bag'
                );
            }

            // 3. Seller notifications (per vendor)
            $vendorIds = VendorOrder::where('order_id', $order->id)->pluck('user_id')->unique();
            foreach ($vendorIds as $vendorId) {
                if ($vendorId && $vendorId > 0) {
                    $vendor = User::find($vendorId);
                    if ($vendor) {
                        self::sendTemplateEmail('seller_new_order', $vendor->email, [
                            'cname'   => $vendor->name,
                            'onumber' => $order->order_number,
                            'oamount' => ($order->currency_sign ?? 'CFA') . ' ' . number_format($order->pay_amount, 0),
                        ]);

                        self::createNotification(
                            'user', $vendorId,
                            'order_placed',
                            'New order #' . $order->order_number . ' received from ' . $order->customer_name,
                            $order->order_number,
                            route('vendor-order-show', $order->id),
                            'fas fa-shopping-cart'
                        );
                    }
                }
            }

            // 4. Admin notification (in-app only, email for high-value)
            if ($gs && !empty($gs->from_email)) {
                self::sendTemplateEmail('admin_new_order', $gs->from_email, [
                    'cname'   => $order->customer_name,
                    'aname'   => 'Admin',
                    'onumber' => $order->order_number,
                    'oamount' => ($order->currency_sign ?? 'CFA') . ' ' . number_format($order->pay_amount, 0),
                ]);
            }

        } catch (\Exception $e) {
            Log::error('FabiliveNotifier::orderPlaced error: ' . $e->getMessage());
        }
    }

    /**
     * Triggered when admin changes order status to "processing".
     */
    public static function orderProcessing(Order $order): void
    {
        try {
            // Buyer email
            self::sendTemplateEmail('order_processing', $order->customer_email, [
                'cname'   => $order->customer_name,
                'onumber' => $order->order_number,
            ]);

            // Buyer in-app
            if ($order->user_id) {
                self::createNotification(
                    'user', $order->user_id,
                    'order_processing',
                    'Your order #' . $order->order_number . ' is being prepared!',
                    $order->order_number,
                    null,
                    'fas fa-cog'
                );
            }
        } catch (\Exception $e) {
            Log::error('FabiliveNotifier::orderProcessing error: ' . $e->getMessage());
        }
    }

    /**
     * Triggered when admin completes an order.
     */
    public static function orderCompleted(Order $order): void
    {
        try {
            // Buyer email
            self::sendTemplateEmail('order_completed', $order->customer_email, [
                'cname'   => $order->customer_name,
                'onumber' => $order->order_number,
            ]);

            // Buyer in-app
            if ($order->user_id) {
                self::createNotification(
                    'user', $order->user_id,
                    'order_completed',
                    'Your order #' . $order->order_number . ' has been completed!',
                    $order->order_number,
                    null,
                    'fas fa-check-circle'
                );
            }

            // Seller in-app
            $vendorIds = VendorOrder::where('order_id', $order->id)->pluck('user_id')->unique();
            foreach ($vendorIds as $vendorId) {
                if ($vendorId && $vendorId > 0) {
                    self::createNotification(
                        'user', $vendorId,
                        'order_completed',
                        'Order #' . $order->order_number . ' has been completed.',
                        $order->order_number,
                        route('vendor-order-show', $order->id),
                        'fas fa-check-circle'
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('FabiliveNotifier::orderCompleted error: ' . $e->getMessage());
        }
    }

    /**
     * Triggered when admin declines an order.
     */
    public static function orderDeclined(Order $order): void
    {
        try {
            // Buyer email (using existing template or custom)
            $template = DB::table('email_templates')->where('email_type', 'order_declined')->first();
            if ($template) {
                self::sendTemplateEmail('order_declined', $order->customer_email, [
                    'cname'   => $order->customer_name,
                    'onumber' => $order->order_number,
                ]);
            } else {
                // Fallback to custom email
                self::sendCustomEmail(
                    $order->customer_email,
                    'Your order ' . $order->order_number . ' has been Declined',
                    'Hello ' . $order->customer_name . ',<br><br>We regret to inform you that your order <strong>#' . $order->order_number . '</strong> has been declined.<br><br>If you were charged, a refund will be processed. Please contact our support team if you have questions.<br><br>Best regards,<br>The Fabilive Team'
                );
            }

            // Buyer in-app
            if ($order->user_id) {
                self::createNotification(
                    'user', $order->user_id,
                    'order_declined',
                    'Your order #' . $order->order_number . ' has been declined.',
                    $order->order_number,
                    null,
                    'fas fa-times-circle'
                );
            }

            // Seller in-app
            $vendorIds = VendorOrder::where('order_id', $order->id)->pluck('user_id')->unique();
            foreach ($vendorIds as $vendorId) {
                if ($vendorId && $vendorId > 0) {
                    self::createNotification(
                        'user', $vendorId,
                        'order_declined',
                        'Order #' . $order->order_number . ' has been declined by admin.',
                        $order->order_number,
                        route('vendor-order-show', $order->id),
                        'fas fa-times-circle'
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('FabiliveNotifier::orderDeclined error: ' . $e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════
    //  DELIVERY EVENTS
    // ══════════════════════════════════════════════════════════

    /**
     * Triggered when a rider is assigned to an order.
     */
    public static function riderAssigned(Order $order, $riderId = null): void
    {
        try {
            // Buyer email
            self::sendTemplateEmail('rider_assigned', $order->customer_email, [
                'cname'   => $order->customer_name,
                'onumber' => $order->order_number,
            ]);

            // Buyer in-app
            if ($order->user_id) {
                self::createNotification(
                    'user', $order->user_id,
                    'rider_assigned',
                    'A delivery rider has been assigned to your order #' . $order->order_number,
                    $order->order_number,
                    null,
                    'fas fa-motorcycle'
                );
            }

            // Rider email + in-app
            if ($riderId) {
                $rider = \App\Models\Rider::find($riderId);
                if ($rider && !empty($rider->email)) {
                    self::sendTemplateEmail('rider_new_job', $rider->email, [
                        'cname'   => $rider->name ?? 'Rider',
                        'onumber' => $order->order_number,
                    ]);
                }

                self::createNotification(
                    'rider', $riderId,
                    'rider_assigned',
                    'New delivery job assigned: Order #' . $order->order_number,
                    $order->order_number,
                    null,
                    'fas fa-motorcycle'
                );
            }

            // Seller in-app
            $vendorIds = VendorOrder::where('order_id', $order->id)->pluck('user_id')->unique();
            foreach ($vendorIds as $vendorId) {
                if ($vendorId && $vendorId > 0) {
                    self::createNotification(
                        'user', $vendorId,
                        'rider_assigned',
                        'Rider assigned to order #' . $order->order_number,
                        $order->order_number,
                        route('vendor-order-show', $order->id),
                        'fas fa-motorcycle'
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('FabiliveNotifier::riderAssigned error: ' . $e->getMessage());
        }
    }

    /**
     * Triggered when an order is delivered.
     */
    public static function orderDelivered(Order $order, $riderId = null): void
    {
        try {
            // Buyer email
            self::sendTemplateEmail('order_delivered', $order->customer_email, [
                'cname'   => $order->customer_name,
                'onumber' => $order->order_number,
            ]);

            // Buyer in-app
            if ($order->user_id) {
                self::createNotification(
                    'user', $order->user_id,
                    'order_delivered',
                    'Your order #' . $order->order_number . ' has been delivered!',
                    $order->order_number,
                    null,
                    'fas fa-box-open'
                );
            }

            // Seller in-app
            $vendorIds = VendorOrder::where('order_id', $order->id)->pluck('user_id')->unique();
            foreach ($vendorIds as $vendorId) {
                if ($vendorId && $vendorId > 0) {
                    self::createNotification(
                        'user', $vendorId,
                        'order_delivered',
                        'Order #' . $order->order_number . ' has been delivered.',
                        $order->order_number,
                        route('vendor-order-show', $order->id),
                        'fas fa-box-open'
                    );
                }
            }

            // Rider in-app
            if ($riderId) {
                self::createNotification(
                    'rider', $riderId,
                    'order_delivered',
                    'Delivery completed for order #' . $order->order_number,
                    $order->order_number,
                    null,
                    'fas fa-check-double'
                );
            }
        } catch (\Exception $e) {
            Log::error('FabiliveNotifier::orderDelivered error: ' . $e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════
    //  FINANCIAL EVENTS
    // ══════════════════════════════════════════════════════════

    /**
     * Triggered when escrow is released and commissions are credited.
     */
    public static function commissionCredited(Order $order): void
    {
        try {
            // Notify each vendor
            $vendorOrders = VendorOrder::where('order_id', $order->id)->get();
            foreach ($vendorOrders as $vOrder) {
                if ($vOrder->user_id && $vOrder->user_id > 0) {
                    $vendor = User::find($vOrder->user_id);
                    if ($vendor) {
                        self::sendTemplateEmail('commission_credited', $vendor->email, [
                            'cname'   => $vendor->name,
                            'onumber' => $order->order_number,
                            'oamount' => ($order->currency_sign ?? 'CFA') . ' ' . number_format($vOrder->price, 0),
                        ]);

                        self::createNotification(
                            'user', $vendor->id,
                            'commission_credited',
                            'Earnings of ' . ($order->currency_sign ?? 'CFA') . ' ' . number_format($vOrder->price, 0) . ' credited for order #' . $order->order_number,
                            $order->order_number,
                            route('vendor-order-show', $order->id),
                            'fas fa-wallet'
                        );
                    }
                }
            }

            // Notify rider if delivery fee was released
            $deliveryJob = $order->deliveryJob;
            if ($deliveryJob && $deliveryJob->rider_id) {
                $rider = \App\Models\Rider::find($deliveryJob->rider_id);
                if ($rider && !empty($rider->email)) {
                    self::sendTemplateEmail('commission_credited', $rider->email, [
                        'cname'   => $rider->name ?? 'Rider',
                        'onumber' => $order->order_number,
                        'oamount' => ($order->currency_sign ?? 'CFA') . ' ' . number_format($order->total_delivery_fee ?? 0, 0),
                    ]);
                }

                self::createNotification(
                    'rider', $deliveryJob->rider_id,
                    'commission_credited',
                    'Delivery fee credited for order #' . $order->order_number,
                    $order->order_number,
                    null,
                    'fas fa-wallet'
                );
            }
        } catch (\Exception $e) {
            Log::error('FabiliveNotifier::commissionCredited error: ' . $e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════
    //  INTERNAL HELPERS
    // ══════════════════════════════════════════════════════════

    /**
     * Send an email using a database template via GeniusMailer.
     */
    private static function sendTemplateEmail(string $type, string $to, array $data): void
    {
        try {
            if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
                return;
            }

            $gs = Generalsetting::safeFirst();
            if (!$gs || !$gs->is_smtp) {
                return;
            }

            $mailer = new GeniusMailer();
            $mailer->sendAutoMail(array_merge([
                'to'   => $to,
                'type' => $type,
            ], $data));
        } catch (\Exception $e) {
            Log::error("FabiliveNotifier::sendTemplateEmail({$type}) error: " . $e->getMessage());
        }
    }

    /**
     * Send a custom (non-template) email via GeniusMailer.
     */
    private static function sendCustomEmail(string $to, string $subject, string $body): void
    {
        try {
            if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
                return;
            }

            $gs = Generalsetting::safeFirst();
            if (!$gs || !$gs->is_smtp) {
                return;
            }

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail([
                'to'      => $to,
                'subject' => $subject,
                'body'    => $body,
            ]);
        } catch (\Exception $e) {
            Log::error("FabiliveNotifier::sendCustomEmail error: " . $e->getMessage());
        }
    }

    /**
     * Create a unified in-app notification.
     */
    private static function createNotification(
        string $recipientType,
        int $recipientId,
        string $type,
        string $message,
        ?string $orderNumber = null,
        ?string $url = null,
        ?string $icon = null
    ): void {
        try {
            // Prevent duplicates: same recipient + type + order within 5 minutes
            $exists = UserNotification::where('recipient_type', $recipientType)
                ->where('recipient_id', $recipientId)
                ->where('type', $type)
                ->where('order_number', $orderNumber)
                ->where('created_at', '>=', now()->subMinutes(5))
                ->exists();

            if ($exists) {
                return;
            }

            UserNotification::create([
                'user_id'        => $recipientType === 'user' ? $recipientId : 0,
                'recipient_type' => $recipientType,
                'recipient_id'   => $recipientId,
                'order_number'   => $orderNumber,
                'type'           => $type,
                'message'        => $message,
                'url'            => $url,
                'icon'           => $icon,
                'is_read'        => 0,
            ]);
        } catch (\Exception $e) {
            Log::error("FabiliveNotifier::createNotification({$type}) error: " . $e->getMessage());
        }
    }
}

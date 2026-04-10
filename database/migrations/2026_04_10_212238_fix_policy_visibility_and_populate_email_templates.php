<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Fix Policy Visibility
        $policies = [
            ['title' => 'Refunds policy', 'slugs' => ['refund-policy', 'refunds-policy', 'refund policy']],
            ['title' => 'Delivery Rates Policy', 'slugs' => ['delivery-rates-policy', 'delivery-rates', 'delivery rates policy']],
            ['title' => 'Limitation of Liability', 'slugs' => ['limitation-of-liability', 'limitation of liability', 'liability']],
            ['title' => 'Dispute Resolution', 'slugs' => ['dispute-resolution', 'dispute resolution']],
            ['title' => 'Policy Updates', 'slugs' => ['policy-updates', 'policy updates', 'updates']],
        ];

        foreach ($policies as $policy) {
            DB::table('pages')
                ->whereIn('slug', $policy['slugs'])
                ->orWhere('title', 'like', '%' . $policy['title'] . '%')
                ->update([
                    'header' => 1,
                    'footer' => 1
                ]);
        }

        // 2. Clear Pages Cache
        Cache::forget('global_pages');

        // 3. Populate Email Templates
        $templates = [
            [
                'email_type' => 'new_registration',
                'email_subject' => 'Welcome to Fabilive - Your Account is Ready!',
                'email_body' => 'Hello {customer_name},<br><br>Welcome to Fabilive! Your account has been successfully created. You can now start shopping or selling on our platform.<br><br>Best regards,<br>The Fabilive Team',
                'status' => 1
            ],
            [
                'email_type' => 'order_confirmed',
                'email_subject' => 'Order Confirmation - {order_number}',
                'email_body' => 'Hello {customer_name},<br><br>Your order #{order_number} has been successfully placed. The total amount is {order_amount}. We will notify you once your order is dispatched.<br><br>Thank you for shopping with us!',
                'status' => 1
            ],
            [
                'email_type' => 'order_shipped',
                'email_subject' => 'Your Order #{order_number} is on the way!',
                'email_body' => 'Hello {customer_name},<br><br>Great news! Your order #{order_number} has been dispatched and is on its way to you.<br><br>Best regards,<br>The Fabilive Team',
                'status' => 1
            ],
            [
                'email_type' => 'order_completed',
                'email_subject' => 'Order Delivered - #{order_number}',
                'email_body' => 'Hello {customer_name},<br><br>Your order #{order_number} has been delivered. We hope you enjoy your purchase!<br><br>Best regards,<br>The Fabilive Team',
                'status' => 1
            ],
            [
                'email_type' => 'vendor_verification',
                'email_subject' => 'Vendor Verification Update',
                'email_body' => 'Hello {customer_name},<br><br>Your vendor verification status has been updated. Please log in to your dashboard to see the details.<br><br>Best regards,<br>The Fabilive Team',
                'status' => 1
            ],
            [
                'email_type' => 'dispute_received',
                'email_subject' => 'New Dispute Notification - #{order_number}',
                'email_body' => 'Hello,<br><br>A new dispute has been opened for order #{order_number}. Please review the details in your dashboard.<br><br>Best regards,<br>The Fabilive Team',
                'status' => 1
            ],
            [
                'email_type' => 'contact_form',
                'email_subject' => 'We received your message',
                'email_body' => 'Hello {customer_name},<br><br>Thank you for contacting us. We have received your message and will get back to you as soon as possible.<br><br>Best regards,<br>The Fabilive Team',
                'status' => 1
            ],
        ];

        foreach ($templates as $template) {
            $exists = DB::table('email_templates')->where('email_type', $template['email_type'])->first();
            if (!$exists) {
                DB::table('email_templates')->insert($template);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy reverse for content updates without backup, keeping empty
    }
};

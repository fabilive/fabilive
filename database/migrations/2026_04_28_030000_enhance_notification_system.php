<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Enrich user_notifications table for unified multi-role notifications
        if (Schema::hasTable('user_notifications')) {
            Schema::table('user_notifications', function (Blueprint $table) {
                if (!Schema::hasColumn('user_notifications', 'recipient_type')) {
                    $table->string('recipient_type', 20)->default('user')->after('id')
                        ->comment('user, rider, admin');
                }
                if (!Schema::hasColumn('user_notifications', 'recipient_id')) {
                    $table->unsignedBigInteger('recipient_id')->nullable()->after('recipient_type')
                        ->comment('FK to users/riders/admins depending on recipient_type');
                }
                if (!Schema::hasColumn('user_notifications', 'type')) {
                    $table->string('type', 50)->nullable()->after('order_number')
                        ->comment('Event type: order_placed, rider_assigned, etc.');
                }
                if (!Schema::hasColumn('user_notifications', 'message')) {
                    $table->text('message')->nullable()->after('type');
                }
                if (!Schema::hasColumn('user_notifications', 'url')) {
                    $table->string('url', 500)->nullable()->after('message')
                        ->comment('Link to relevant entity');
                }
                if (!Schema::hasColumn('user_notifications', 'icon')) {
                    $table->string('icon', 50)->nullable()->after('url')
                        ->comment('FontAwesome icon class');
                }
            });

            // Backfill existing rows: set recipient_type and recipient_id from user_id
            DB::table('user_notifications')
                ->whereNull('recipient_id')
                ->whereNotNull('user_id')
                ->update([
                    'recipient_type' => 'user',
                    'recipient_id' => DB::raw('user_id'),
                ]);
        }

        // 2. Seed missing email templates
        $templates = [
            [
                'email_type'    => 'order_placed',
                'email_subject' => 'Order Placed — #{order_number}',
                'email_body'    => 'Hello {customer_name},<br><br>Thank you for your order! Your order <strong>#{order_number}</strong> has been placed successfully.<br><br><strong>Order Total:</strong> {order_amount}<br><br>We\'ll notify you as your order progresses. You can track it anytime from your dashboard.<br><br>Happy shopping!<br>The Fabilive Team',
                'status'        => 1,
            ],
            [
                'email_type'    => 'seller_new_order',
                'email_subject' => '🛒 New Order Received — #{order_number}',
                'email_body'    => 'Hello {customer_name},<br><br>Great news! You have received a new order <strong>#{order_number}</strong> worth <strong>{order_amount}</strong>.<br><br>Please log in to your seller dashboard to review and prepare the order for fulfillment.<br><br>Best regards,<br>The Fabilive Team',
                'status'        => 1,
            ],
            [
                'email_type'    => 'payment_confirmed',
                'email_subject' => 'Payment Confirmed — #{order_number}',
                'email_body'    => 'Hello {customer_name},<br><br>Your payment for order <strong>#{order_number}</strong> has been confirmed and is now being processed.<br><br>We\'ll keep you updated on your order status.<br><br>Best regards,<br>The Fabilive Team',
                'status'        => 1,
            ],
            [
                'email_type'    => 'order_processing',
                'email_subject' => 'Your Order #{order_number} is Being Prepared',
                'email_body'    => 'Hello {customer_name},<br><br>Your order <strong>#{order_number}</strong> is now being prepared for shipment. We\'ll let you know once it\'s on its way!<br><br>Best regards,<br>The Fabilive Team',
                'status'        => 1,
            ],
            [
                'email_type'    => 'rider_assigned',
                'email_subject' => 'Rider Assigned — Order #{order_number}',
                'email_body'    => 'Hello {customer_name},<br><br>A delivery rider has been assigned to your order <strong>#{order_number}</strong>. Your package is on its way!<br><br>Best regards,<br>The Fabilive Team',
                'status'        => 1,
            ],
            [
                'email_type'    => 'rider_new_job',
                'email_subject' => '📦 New Delivery Job — Order #{order_number}',
                'email_body'    => 'Hello {customer_name},<br><br>You have been assigned a new delivery job for order <strong>#{order_number}</strong>.<br><br>Please check your rider dashboard for pickup and delivery details.<br><br>Best regards,<br>The Fabilive Team',
                'status'        => 1,
            ],
            [
                'email_type'    => 'order_delivered',
                'email_subject' => 'Your Order #{order_number} Has Been Delivered!',
                'email_body'    => 'Hello {customer_name},<br><br>Your order <strong>#{order_number}</strong> has been delivered successfully! We hope you love your purchase.<br><br>If you have any questions, please don\'t hesitate to reach out.<br><br>Best regards,<br>The Fabilive Team',
                'status'        => 1,
            ],
            [
                'email_type'    => 'commission_credited',
                'email_subject' => 'Commission Credited — Order #{order_number}',
                'email_body'    => 'Hello {customer_name},<br><br>Your earnings of <strong>{order_amount}</strong> for order <strong>#{order_number}</strong> have been credited to your wallet.<br><br>You can view your balance and request a withdrawal from your dashboard.<br><br>Best regards,<br>The Fabilive Team',
                'status'        => 1,
            ],
            [
                'email_type'    => 'admin_new_order',
                'email_subject' => '[Admin] New Order — #{order_number}',
                'email_body'    => 'Hello {admin_name},<br><br>A new order <strong>#{order_number}</strong> has been placed by <strong>{customer_name}</strong> for <strong>{order_amount}</strong>.<br><br>Please review it in the admin dashboard.<br><br>— Fabilive System',
                'status'        => 1,
            ],
        ];

        foreach ($templates as $template) {
            $exists = DB::table('email_templates')
                ->where('email_type', $template['email_type'])
                ->first();
            if (!$exists) {
                DB::table('email_templates')->insert($template);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('user_notifications')) {
            Schema::table('user_notifications', function (Blueprint $table) {
                $cols = ['recipient_type', 'recipient_id', 'type', 'message', 'url', 'icon'];
                foreach ($cols as $col) {
                    if (Schema::hasColumn('user_notifications', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }

        DB::table('email_templates')->whereIn('email_type', [
            'order_placed', 'seller_new_order', 'payment_confirmed', 'order_processing',
            'rider_assigned', 'rider_new_job', 'order_delivered', 'commission_credited',
            'admin_new_order',
        ])->delete();
    }
};

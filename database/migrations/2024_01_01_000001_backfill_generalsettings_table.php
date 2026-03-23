<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $columns = [
                'logo', 'favicon', 'title', 'copyright', 'colors', 'loader', 'admin_loader', 'talkto', 'disqus',
                'currency_format', 'withdraw_fee', 'withdraw_charge', 'shipping_cost', 'mail_driver', 'mail_host',
                'mail_port', 'mail_encryption', 'mail_user', 'mail_pass', 'is_affilate', 'affilate_charge',
                'affilate_banner', 'fixed_commission', 'percentage_commission', 'multiple_shipping', 'vendor_ship_info',
                'is_verification_email', 'wholesell', 'error_banner_404', 'error_banner_500', 'popup_title',
                'popup_text', 'popup_background', 'invoice_logo', 'user_image', 'vendor_color', 'is_secure',
                'paypal_business', 'footer_logo', 'paytm_merchant', 'maintain_text', 'flash_count', 'hot_count',
                'new_count', 'sale_count', 'best_seller_count', 'popular_count', 'top_rated_count', 'big_save_count',
                'trending_count', 'page_count', 'seller_product_count', 'wishlist_count', 'vendor_page_count',
                'min_price', 'max_price', 'product_page', 'post_count', 'wishlist_page', 'decimal_separator',
                'thousand_separator', 'version', 'is_reward', 'reward_point', 'reward_dolar', 'physical', 'digital',
                'license', 'affilite', 'header_color', 'breadcrumb_banner', 'partner_title', 'partner_text',
                'deal_title', 'deal_details', 'deal_time', 'deal_background', 'delivery_base_fee', 'delivery_stopover_fee',
                'rider_percentage_commission'
            ];

            foreach ($columns as $column) {
                if (!Schema::hasColumn('generalsettings', $column)) {
                    $table->text($column)->nullable(); // Use text for safety as many fields are long strings or JSON
                }
            }

            if (!Schema::hasColumn('generalsettings', 'is_maintain')) {
                $table->tinyInteger('is_maintain')->default(0);
            }
        });

        // Insert first row if empty
        if (DB::table('generalsettings')->count() == 0) {
            DB::table('generalsettings')->insert([
                'title' => 'Fabilive',
                'is_maintain' => 0
            ]);
        }
    }

    public function down(): void
    {
        // No down migration for accidental deletions
    }
};

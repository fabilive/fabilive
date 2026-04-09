<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Generalsetting extends Model
{
    protected $fillable = ['logo', 'favicon', 'title', 'copyright', 'colors', 'loader', 'admin_loader', 'talkto', 'disqus', 'currency_format', 'withdraw_fee', 'withdraw_charge', 'shipping_cost', 'mail_driver', 'mail_host', 'mail_port', 'mail_encryption', 'mail_user', 'mail_pass', 'from_email', 'from_name', 'is_affilate', 'affilate_charge', 'affilate_banner', 'fixed_commission', 'percentage_commission', 'multiple_shipping', 'vendor_ship_info', 'is_verification_email', 'wholesell', 'is_capcha', 'error_banner_404', 'error_banner_500', 'popup_title', 'popup_text', 'popup_background', 'invoice_logo', 'user_image', 'vendor_color', 'is_secure', 'paypal_business', 'footer_logo', 'paytm_merchant', 'maintain_text', 'flash_count', 'hot_count', 'new_count', 'sale_count', 'best_seller_count', 'popular_count', 'top_rated_count', 'big_save_count', 'trending_count', 'page_count', 'seller_product_count', 'wishlist_count', 'vendor_page_count', 'min_price', 'max_price', 'product_page', 'post_count', 'wishlist_page', 'decimal_separator', 'thousand_separator', 'version', 'is_reward', 'reward_point', 'reward_dolar', 'physical', 'digital', 'license', 'affilite', 'header_color', 'capcha_secret_key', 'capcha_site_key', 'referral_amount', 'referral_bonus', 'breadcrumb_banner', 'partner_title', 'partner_text', 'deal_title', 'deal_details', 'deal_time', 'deal_background', 'delivery_base_fee', 'delivery_stopover_fee', 'rider_percentage_commission', 'custom_referral_bonus', 'same_servicearea_delivery_fee'];

    public $timestamps = false;

    public static $dbAvailable = null;

    public static function isDbValid()
    {
        if (self::$dbAvailable !== null) {
            return self::$dbAvailable;
        }
        try {
            \DB::connection()->getPdo();
            self::$dbAvailable = true;
        } catch (\Exception $e) {
            self::$dbAvailable = false;
        }
        return self::$dbAvailable;
    }

    /**
     * Get the first general setting record with a robust fail-safe.
     * 
     * @return object|\stdClass
     */
    public static function safeFirst()
    {
        try {
            if (self::isDbValid()) {
                $gs = cache()->remember('generalsettings', now()->addDay(), function () {
                    return \DB::table('generalsettings')->first();
                });
                if ($gs) return $gs;
            }
        } catch (\Exception $e) {
            // Silently fail to in-memory defaults
        }

        // 3. Fallback to in-memory defaults if DB is down or record is missing
        $gs = new \stdClass();
        $gs->title = "Fabilive";
        
        // Reset Logo to use database-first behavior (respecting user's Admin Dashboard)
        $gs->logo = $gs->logo ?? "logo.png";
        $gs->favicon = "favicon.png";
        $gs->is_admin_loader = 0;
        $gs->wholesell = 0;
        $gs->is_capcha = 0;
        $gs->rtl = 0;
        $gs->is_affilite = 0;
        $gs->affilite = 0;
        $gs->physical = 1;
        $gs->digital = 1;
        $gs->license = 1;
        $gs->listing = 1;
        $gs->vendor_ship_info = 1;
        $gs->tawk_to = '';
        $gs->is_tawk = 0;
        $gs->currency_format = 0;
        $gs->fixed_commission = 0;
        $gs->percentage_commission = 0;
        $gs->multiple_shipping = 0;
        $gs->multiple_packaging = 0;
        $gs->guest_checkout = 1;
        $gs->is_maintain = 0;
        $gs->is_verification_email = 0;
        $gs->is_smtp = 0;
        $gs->admin_loader = 'loader.gif';
        $gs->decimal_separator = '.';
        $gs->thousand_separator = ',';

        return $gs;
    }

    public function upload($name, $file, $oldname)
    {
        $destination = public_path('assets/images');
        $file->move($destination, $name);
        if ($oldname != null && $oldname !== 'logo.png' && $oldname !== 'noimage.png') {
            $oldPath = public_path('assets/images/'.$oldname);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagesetting extends Model
{
    protected $fillable = ['contact_email', 'street', 'phone', 'fax', 'email', 'site', 'best_seller_banner', 'best_seller_banner_link', 'big_save_banner', 'big_save_banner_link', 'best_seller_banner1', 'best_seller_banner_link1', 'big_save_banner1', 'big_save_banner_link1', 'partners', 'bottom_small', 'rightbanner1', 'rightbanner2', 'rightbannerlink1', 'rightbannerlink2', 'home', 'blog', 'faq', 'contact', 'category', 'arrival_section', 'our_services', 'blog', 'popular_products', 'third_left_banner', 'slider', 'flash_deal', 'deal_of_the_day', 'best_sellers', 'partner', 'top_big_trending', 'top_brand', 'big_save_banner_subtitle', 'big_save_banner_title', 'big_save_banner_text', 'top_banner', 'large_banner', 'best_selling', 'bottom_banner', 'newsletter'];

    public $timestamps = false;

    /**
     * Get the first page setting record with a robust fail-safe.
     * 
     * @return object|\stdClass
     */
    public static function safeFirst()
    {
        try {
            if (Generalsetting::isDbValid()) {
                $ps = cache()->remember('pagesettings', now()->addDay(), function () {
                    return \DB::table('pagesettings')->first();
                });
                if ($ps) return $ps;
            }
        } catch (\Exception $e) {
            // Silently fail to in-memory defaults
        }

        // Fallback to in-memory defaults if DB is down or record is missing
        $ps = new \stdClass();
        
        // Essential visibility flags set to 1 by default
        $ps->slider = 1;
        $ps->arrival_section = 1;
        $ps->category = 1;
        $ps->popular_products = 1;
        $ps->featured_category = 1;
        $ps->best_sellers = 1;
        $ps->top_big_trending = 1;
        $ps->top_brand = 1;
        $ps->blog = 1;
        $ps->faq = 1;
        $ps->contact = 1;
        $ps->deal_of_the_day = 1;
        $ps->partner = 1;
        $ps->our_services = 1;
        $ps->newsletter = 1;
        $ps->bottom_banner = 0; // Optional
        
        return $ps;
    }

    public function upload($name, $file, $oldname)
    {
        $destination = public_path('assets/images');
        $file->move($destination, $name);
        if ($oldname != null) {
            $oldPath = public_path('assets/images/'.$oldname);
            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }
    }
}

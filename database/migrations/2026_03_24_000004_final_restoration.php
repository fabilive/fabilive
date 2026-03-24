<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Restore reCAPTCHA Settings
        if (Schema::hasTable('generalsettings')) {
            $updateData = [];
            
            if (Schema::hasColumn('generalsettings', 'is_capcha')) {
                $updateData['is_capcha'] = 1;
            }
            
            if (Schema::hasColumn('generalsettings', 'capcha_site_key') && env('NOCAPTCHA_SITEKEY')) {
                $updateData['capcha_site_key'] = env('NOCAPTCHA_SITEKEY');
            }
            
            if (Schema::hasColumn('generalsettings', 'capcha_secret_key') && env('NOCAPTCHA_SECRET')) {
                $updateData['capcha_secret_key'] = env('NOCAPTCHA_SECRET');
            }
            
            if (!empty($updateData)) {
                DB::table('generalsettings')->update($updateData);
            }
        }

        // 2. Restore Sliders
        if (Schema::hasTable('sliders') && DB::table('sliders')->count() == 0) {
            DB::table('sliders')->insert([
                [
                    'photo' => '1571217730slider1.jpg',
                    'title_text' => 'Premium Electronics',
                    'subtitle_text' => 'Get the best deals on the latest gadgets',
                    'details_text' => 'Up to 50% OFF on all electronics',
                    'link' => '/category/electronics',
                    'position' => 'left'
                ],
                [
                    'photo' => '1571217740slider2.jpg',
                    'title_text' => 'Fashion Trends 2026',
                    'subtitle_text' => 'New Season Collection',
                    'details_text' => 'Explore the latest styles',
                    'link' => '/category/fashion',
                    'position' => 'center'
                ],
                [
                    'photo' => '1571217750slider3.jpg',
                    'title_text' => 'Sustainable Living',
                    'subtitle_text' => 'Eco-friendly products for your home',
                    'details_text' => 'Shop responsibly today',
                    'link' => '/category/home',
                    'position' => 'right'
                ]
            ]);
        }

        // 3. Ensure Categories are active
        if (Schema::hasTable('categories')) {
            DB::table('categories')->update(['status' => 1]);
        }
        if (Schema::hasTable('subcategories')) {
            DB::table('subcategories')->update(['status' => 1]);
        }

        // 4. Fallback for Blog Category if not seeded
        if (Schema::hasTable('blog_categories') && DB::table('blog_categories')->count() == 0) {
            DB::table('blog_categories')->insert([
                'name' => 'General',
                'slug' => 'general'
            ]);
        }
    }

    public function down(): void {}
};

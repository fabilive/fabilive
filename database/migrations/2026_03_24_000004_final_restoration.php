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
            
            // Note: Keys must be set in .env
            
            if (!empty($updateData)) {
                DB::table('generalsettings')->update($updateData);
            }
        }

        // 2. Force Restore Sliders (Delete existing first to ensure 3)
        if (Schema::hasTable('sliders')) {
            DB::table('sliders')->truncate();
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

        // 3. Ensure Categories exist (Restoring Product Sub Pages)
        if (Schema::hasTable('categories') && DB::table('categories')->count() == 0) {
            DB::table('categories')->insert([
                ['name' => 'Electronics', 'slug' => 'electronics', 'status' => 1, 'is_featured' => 1, 'photo' => 'category1.jpg'],
                ['name' => 'Fashion', 'slug' => 'fashion', 'status' => 1, 'is_featured' => 1, 'photo' => 'category2.jpg'],
                ['name' => 'Home & Garden', 'slug' => 'home-garden', 'status' => 1, 'is_featured' => 1, 'photo' => 'category3.jpg'],
                ['name' => 'Smartphone', 'slug' => 'smartphone', 'status' => 1, 'is_featured' => 1, 'photo' => 'category4.jpg'],
                ['name' => 'Camera', 'slug' => 'camera', 'status' => 1, 'is_featured' => 1, 'photo' => 'category5.jpg']
            ]);
        }

        // 4. Ensure Blog Category exists
        if (Schema::hasTable('blog_categories') && DB::table('blog_categories')->count() == 0) {
            DB::table('blog_categories')->insert([
                'name' => 'General',
                'slug' => 'general'
            ]);
        }
    }

    public function down(): void {}
};

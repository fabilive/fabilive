<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix missing column in generalsettings
        if (Schema::hasTable('generalsettings')) {
            if (!Schema::hasColumn('generalsettings', 'product_affilate')) {
                Schema::table('generalsettings', function (Blueprint $table) {
                    $table->integer('product_affilate')->default(1)->after('is_affilate');
                });
            } else {
                DB::table('generalsettings')->update(['product_affilate' => 1]);
            }
        }

        // 2. Force Restore Sliders (Truncate first)
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

        // 3. Force Seed Categories (Ensure Product Sub Pages work)
        if (Schema::hasTable('categories')) {
            // Only seed if empty to avoid duplicates if partially run
            if (DB::table('categories')->count() == 0) {
                DB::table('categories')->insert([
                    ['name' => 'Electronics', 'slug' => 'electronics', 'status' => 1, 'is_featured' => 1, 'photo' => 'category1.jpg'],
                    ['name' => 'Fashion', 'slug' => 'fashion', 'status' => 1, 'is_featured' => 1, 'photo' => 'category2.jpg'],
                    ['name' => 'Home & Garden', 'slug' => 'home-garden', 'status' => 1, 'is_featured' => 1, 'photo' => 'category3.jpg'],
                    ['name' => 'Smartphone', 'slug' => 'smartphone', 'status' => 1, 'is_featured' => 1, 'photo' => 'category4.jpg'],
                    ['name' => 'Camera', 'slug' => 'camera', 'status' => 1, 'is_featured' => 1, 'photo' => 'category5.jpg']
                ]);
            } else {
                DB::table('categories')->update(['status' => 1]);
            }
        }
        
        // 4. Ensure Blog Categories exist
        if (Schema::hasTable('blog_categories') && DB::table('blog_categories')->count() == 0) {
            DB::table('blog_categories')->insert([
                ['name' => 'Technology', 'slug' => 'technology'],
                ['name' => 'Fashion', 'slug' => 'fashion-blog'],
                ['name' => 'Lifestyle', 'slug' => 'lifestyle']
            ]);
        }
    }

    public function down(): void
    {
        // No rollback needed for restoration
    }
};

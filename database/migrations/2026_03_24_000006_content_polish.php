<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update Sliders with REAL image filenames found on server
        if (Schema::hasTable('sliders')) {
            DB::table('sliders')->truncate();
            DB::table('sliders')->insert([
                [
                    'photo' => '1750049373industries-consumer-electronicsjpeg.jpeg',
                    'title_text' => 'Premium Electronics',
                    'subtitle_text' => 'Get the best deals on the latest gadgets',
                    'details_text' => 'Up to 50% OFF on all electronics',
                    'link' => '/category/electronics',
                    'position' => 1
                ],
                [
                    'photo' => '164740562317png.png',
                    'title_text' => 'Fashion Trends 2026',
                    'subtitle_text' => 'New Season Collection',
                    'details_text' => 'Explore the latest styles',
                    'link' => '/category/fashion',
                    'position' => 2
                ],
                [
                    'photo' => '1742434310Screenshot2025-03-20at092951png.png',
                    'title_text' => 'Sustainable Living',
                    'subtitle_text' => 'Eco-friendly products for your home',
                    'details_text' => 'Shop responsibly today',
                    'link' => '/category/home-garden',
                    'position' => 3
                ]
            ]);
        }

        // 2. Update Categories with REAL image filenames
        if (Schema::hasTable('categories')) {
            DB::table('categories')->truncate();
            $categories = [
                ['name' => 'Electronics', 'slug' => 'electronics', 'photo' => 'category_electronic.png'],
                ['name' => 'Fashion', 'slug' => 'fashion', 'photo' => 'category_fashion.png'],
                ['name' => 'Home & Garden', 'slug' => 'home-garden', 'photo' => '1568878596home.jpg'],
                ['name' => 'Smartphone', 'slug' => 'smartphone', 'photo' => 'category_smartphone.png'],
                ['name' => 'Camera', 'slug' => 'camera', 'photo' => 'category_camera.png']
            ];
            foreach ($categories as $cat) {
                DB::table('categories')->insert(array_merge($cat, ['status' => 1, 'is_featured' => 1]));
            }
        }

        // 3. Seed Subcategories (Ensure Sub Pages populate)
        if (Schema::hasTable('subcategories')) {
            DB::table('subcategories')->truncate();
            $cat_ids = DB::table('categories')->pluck('id', 'slug');
            if (isset($cat_ids['electronics'])) {
                DB::table('subcategories')->insert([
                    ['category_id' => $cat_ids['electronics'], 'name' => 'Laptops', 'slug' => 'laptops', 'status' => 1],
                    ['category_id' => $cat_ids['electronics'], 'name' => 'Audio', 'slug' => 'audio', 'status' => 1]
                ]);
            }
            if (isset($cat_ids['fashion'])) {
                DB::table('subcategories')->insert([
                    ['category_id' => $cat_ids['fashion'], 'name' => 'Clothing', 'slug' => 'clothing', 'status' => 1],
                    ['category_id' => $cat_ids['fashion'], 'name' => 'Shoes', 'slug' => 'shoes', 'status' => 1]
                ]);
            }
        }

        // 4. Seed Initial Products (Ensure Product detail pages work)
        if (Schema::hasTable('products')) {
            // Only seed if empty to avoid bloat
            if (DB::table('products')->count() == 0) {
                $cat_ids = DB::table('categories')->pluck('id', 'slug');
                $sub_ids = DB::table('subcategories')->pluck('id', 'slug');
                
                DB::table('products')->insert([
                    [
                        'sku' => 'ELEC001',
                        'name' => 'High Performance Laptop',
                        'slug' => 'high-performance-laptop',
                        'category_id' => $cat_ids['electronics'] ?? 0,
                        'subcategory_id' => $sub_ids['laptops'] ?? 0,
                        'photo' => '1744212714Akshyjpeg.jpeg', // from category images dir
                        'price' => 1200,
                        'details' => 'Premium laptop for professionals',
                        'status' => 1,
                        'stock' => 10
                    ],
                    [
                        'sku' => 'FASH001',
                        'name' => 'Urban Fashion Tee',
                        'slug' => 'urban-fashion-tee',
                        'category_id' => $cat_ids['fashion'] ?? 0,
                        'subcategory_id' => $sub_ids['clothing'] ?? 0,
                        'photo' => '1568708973f12.jpg',
                        'price' => 25,
                        'details' => 'Stylish and comfortable t-shirt',
                        'status' => 1,
                        'stock' => 100
                    ]
                ]);
            }
        }
    }

    public function down(): void {}
};

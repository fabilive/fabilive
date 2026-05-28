<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $deals = [
            ['name' => 'Phones & Tablets', 'slug' => 'phones-tablets', 'image' => '3d_smartphone.png'],
            ['name' => 'Fashion deals', 'slug' => 'fashion-deals', 'image' => 'category_fashion.png'],
            ['name' => 'Appliances deals', 'slug' => 'appliances-deals', 'image' => 'garden.png'],
            ['name' => 'TV & Audio deals', 'slug' => 'tv-audio-deals', 'image' => 'category_electronic.png'],
            ['name' => 'Beauty Must Have', 'slug' => 'beauty-deals', 'image' => 'beauty.png'],
            ['name' => 'Sneakers deals', 'slug' => 'sneakers-deals', 'image' => 'category_sport.png'],
            ['name' => 'New Arrival', 'slug' => 'new-arrival', 'image' => 'category_camera.png'],
            ['name' => 'Mobile Accessories deals', 'slug' => 'mobile-accessories-deals', 'image' => 'category_surveillance.png'],
            ['name' => 'Home & Office deals', 'slug' => 'home-office-deals', 'image' => 'building.png'],
            ['name' => 'Beverages deals', 'slug' => 'beverages-deals', 'image' => 'food.png'],
            ['name' => 'Computing deals', 'slug' => 'computing-deals', 'image' => '3d_laptop.png'],
            ['name' => 'Buy Now, Pay Small Small', 'slug' => 'buy-now-pay-small-small', 'image' => 'services.png']
        ];

        \Illuminate\Support\Facades\DB::table('deal_pages')->truncate();

        foreach ($deals as $index => $deal) {
            \Illuminate\Support\Facades\DB::table('deal_pages')->insert([
                'name' => $deal['name'],
                'slug' => $deal['slug'],
                'image' => $deal['image'],
                'sort_order' => $index,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

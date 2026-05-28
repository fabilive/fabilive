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
        Schema::create('deal_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed the 12 initial deal pages
        $initialDeals = [
            ['name' => 'Phones & Tablets', 'slug' => 'phones-tablets'],
            ['name' => 'Fashion Deals', 'slug' => 'fashion-deals'],
            ['name' => 'Appliances Deals', 'slug' => 'appliances-deals'],
            ['name' => 'Computing', 'slug' => 'computing'],
            ['name' => 'Supermarket', 'slug' => 'supermarket'],
            ['name' => 'Kiddies corner', 'slug' => 'kiddies-corner'],
            ['name' => 'Health & Beauty', 'slug' => 'health-beauty'],
            ['name' => 'Electronics Deals', 'slug' => 'electronics-deals'],
            ['name' => 'Food delivery', 'slug' => 'food-delivery'],
            ['name' => 'Furniture deals', 'slug' => 'furniture-deals'],
            ['name' => 'Games and consoles', 'slug' => 'games-and-consoles'],
            ['name' => 'Farm produces', 'slug' => 'farm-produces'],
        ];

        foreach ($initialDeals as $index => $deal) {
            \Illuminate\Support\Facades\DB::table('deal_pages')->insert([
                'name' => $deal['name'],
                'slug' => $deal['slug'],
                'sort_order' => $index,
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
        Schema::dropIfExists('deal_pages');
    }
};

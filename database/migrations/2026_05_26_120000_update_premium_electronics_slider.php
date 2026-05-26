<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('sliders')) {
            // Update title text from 'Premium Electronics' to 'Premium Products'
            // and update details text to 'Up to 50% OFF on all Products'
            DB::table('sliders')
                ->where('title_text', 'Premium Electronics')
                ->update([
                    'title_text' => 'Premium Products',
                    'details_text' => 'Up to 50% OFF on all Products'
                ]);

            // Also check for case variations or details text variation "all electronics"
            DB::table('sliders')
                ->where('details_text', 'like', '%all electronics%')
                ->update([
                    'details_text' => 'Up to 50% OFF on all Products'
                ]);
            
            // Clear homepage slider cache to make the change visible immediately
            cache()->forget('homepage_sliders');
            try {
                \Illuminate\Support\Facades\Artisan::call('cache:clear');
                \Illuminate\Support\Facades\Artisan::call('view:clear');
            } catch (\Exception $e) {}
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('sliders')) {
            DB::table('sliders')
                ->where('title_text', 'Premium Products')
                ->where('details_text', 'Up to 50% OFF on all Products')
                ->update([
                    'title_text' => 'Premium Electronics',
                    'details_text' => 'Up to 50% OFF on all electronics'
                ]);
        }
    }
};

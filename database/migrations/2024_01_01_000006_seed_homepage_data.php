<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix sliders table
        Schema::table('sliders', function (Blueprint $table) {
            $cols = [
                'subtitle_text', 'subtitle_size', 'subtitle_color', 'subtitle_anime',
                'title_size', 'title_color', 'title_anime',
                'details_text', 'details_size', 'details_color', 'details_anime',
                '3d_model', 'video'
            ];
            foreach ($cols as $col) {
                if (!Schema::hasColumn('sliders', $col)) {
                    $table->string($col)->nullable();
                }
            }
        });

        // 2. Fix arrival_sections table
        Schema::table('arrival_sections', function (Blueprint $table) {
            $cols = ['header', 'up_sale', 'url'];
            foreach ($cols as $col) {
                if (!Schema::hasColumn('arrival_sections', $col)) {
                    $table->string($col)->nullable();
                }
            }
        });

        // 3. Seed sliders if empty
        if (DB::table('sliders')->count() == 0) {
            DB::table('sliders')->insert([
                'title_text' => 'Welcome to Fabilive',
                'subtitle_text' => 'Best Ecommerce Platform',
                'details_text' => 'Discover our exclusive collection.',
                'photo' => 'default_slider.jpg',
                'link' => '#',
                'position' => 0
            ]);
        }

        // 4. Seed arrival_sections if empty
        if (DB::table('arrival_sections')->count() == 0) {
            DB::table('arrival_sections')->insert([
                [
                    'title' => 'New Arrival',
                    'header' => 'Fresh Collection',
                    'up_sale' => 'Up to 50% Off',
                    'photo' => 'default_arrival.jpg',
                    'url' => '#'
                ],
                [
                    'title' => 'Trendy items',
                    'header' => 'Most Popular',
                    'up_sale' => 'Limited Edition',
                    'photo' => 'default_arrival_2.jpg',
                    'url' => '#'
                ]
            ]);
        }
    }

    public function down(): void {}
};

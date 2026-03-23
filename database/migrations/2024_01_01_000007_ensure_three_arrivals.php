<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('arrival_sections')) {
            $count = DB::table('arrival_sections')->count();
            if ($count < 3) {
                for ($i = $count; $i < 3; $i++) {
                    DB::table('arrival_sections')->insert([
                        'title' => 'Special Arrival ' . ($i + 1),
                        'header' => 'Exclusive Offer',
                        'up_sale' => 'Limited Time',
                        'photo' => 'default_arrival.jpg',
                        'url' => '#'
                    ]);
                }
            }
        }
    }

    public function down(): void {}
};

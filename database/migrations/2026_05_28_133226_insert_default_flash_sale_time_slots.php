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
        DB::table('flash_sale_time_slots')->insert([
            ['start_time' => '10:00:00', 'end_time' => '13:59:59', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['start_time' => '14:00:00', 'end_time' => '16:59:59', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['start_time' => '17:00:00', 'end_time' => '18:59:59', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['start_time' => '19:00:00', 'end_time' => '23:59:59', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure columns exist and match Model + Controller usage
        Schema::table('service_areas', function (Blueprint $table) {
            if (!Schema::hasColumn('service_areas', 'location')) {
                $table->string('location')->nullable()->after('name');
            }
            if (!Schema::hasColumn('service_areas', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('location');
            }
            if (!Schema::hasColumn('service_areas', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('service_areas', 'base_fee')) {
                $table->decimal('base_fee', 15, 4)->default(0)->after('price');
            }
            if (!Schema::hasColumn('service_areas', 'stopover_fee')) {
                $table->decimal('stopover_fee', 15, 4)->default(0)->after('base_fee');
            }
        });

        // 2. Add "Limbe" as requested (only if it doesn't exist)
        $exists = DB::table('service_areas')->where('name', 'Limbe')->orWhere('location', 'Limbe')->first();
        if (!$exists) {
            DB::table('service_areas')->insert([
                'name' => 'Limbe',
                'location' => 'Limbe',
                'latitude' => 4.0167,
                'longitude' => 9.2167,
                'price' => 0,
                'base_fee' => 0,
                'stopover_fee' => 0,
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
        Schema::table('service_areas', function (Blueprint $table) {
            $table->dropColumn(['location', 'latitude', 'longitude', 'base_fee', 'stopover_fee']);
        });
    }
};

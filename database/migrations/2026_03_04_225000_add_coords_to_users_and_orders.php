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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'lat')) {
                $table->decimal('lat', 10, 7)->nullable();
            }
            if (!Schema::hasColumn('users', 'lng')) {
                $table->decimal('lng', 10, 7)->nullable();
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'customer_lat')) {
                $table->decimal('customer_lat', 10, 7)->nullable();
            }
            if (!Schema::hasColumn('orders', 'customer_lng')) {
                $table->decimal('customer_lng', 10, 7)->nullable();
            }
            if (!Schema::hasColumn('orders', 'service_area_id')) {
                $table->unsignedBigInteger('service_area_id')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['customer_lat', 'customer_lng', 'service_area_id']);
        });
    }
};

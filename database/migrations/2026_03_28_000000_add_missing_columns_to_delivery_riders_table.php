<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add missing columns to delivery_riders table
        Schema::table('delivery_riders', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_riders', 'order_id')) {
                $table->integer('order_id')->nullable();
                $table->integer('product_id')->nullable();
                $table->integer('vendor_id')->nullable();
                $table->integer('rider_id')->nullable();
                $table->integer('service_area_id')->nullable();
                $table->integer('pickup_point_id')->nullable();
                $table->string('phone_number')->nullable();
                $table->string('status')->nullable();
                $table->text('more_info')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_riders', function (Blueprint $table) {
            $table->dropColumn([
                'order_id',
                'product_id',
                'vendor_id',
                'rider_id',
                'service_area_id',
                'pickup_point_id',
                'phone_number',
                'status',
                'more_info'
            ]);
        });
    }
};

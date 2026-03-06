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
        if (!Schema::hasTable('delivery_jobs')) {
            Schema::create('delivery_jobs', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id'); // Match orders.id which is int(11)
            $table->unsignedInteger('buyer_id'); // Match users.id which is int(10) unsigned
            $table->string('status')->default('pending_readiness'); 
            $table->unsignedBigInteger('service_area_id')->nullable();
            
            $table->decimal('base_fee', 15, 4)->default(0);
            $table->decimal('stopover_fee', 15, 4)->default(0);
            $table->integer('sellers_count')->default(0);
            $table->decimal('delivery_fee_total', 15, 4)->default(0);
            
            $table->decimal('platform_delivery_commission', 15, 4)->default(0);
            $table->decimal('rider_earnings', 15, 4)->default(0);
            
            $table->unsignedInteger('assigned_rider_id')->nullable();
            $table->string('proof_photo')->nullable();
            
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();

            // Explicitly set the types if needed, but the foreign key should handle it if types match exactly
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_rider_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        if (!Schema::hasTable('delivery_job_stops')) {
            Schema::create('delivery_job_stops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_job_id');
            $table->string('type'); // pickup, dropoff
            $table->unsignedInteger('seller_id')->nullable();
            $table->integer('sequence')->default(0);
            $table->string('status')->default('pending'); // pending, ready, arrived, picked_up
            
            $table->text('location_text')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamps();

            $table->foreign('delivery_job_id')->references('id')->on('delivery_jobs')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('delivery_job_events')) {
            Schema::create('delivery_job_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_job_id');
            $table->string('actor_type'); // system, admin, rider, seller, buyer
            $table->unsignedInteger('actor_id')->nullable();
            $table->string('event');
            $table->json('meta_json')->nullable();
            $table->timestamps();

            $table->foreign('delivery_job_id')->references('id')->on('delivery_jobs')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_job_events');
        Schema::dropIfExists('delivery_job_stops');
        Schema::dropIfExists('delivery_jobs');
    }
};

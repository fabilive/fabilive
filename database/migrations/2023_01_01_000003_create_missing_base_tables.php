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
        if (!Schema::hasTable('vendor_orders')) {
            Schema::create('vendor_orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->string('order_number')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->integer('qty')->default(0);
                $table->decimal('price', 15, 4)->default(0);
                $table->string('status')->default('pending');
                $table->decimal('delivery_fee', 15, 4)->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('shippings')) {
            Schema::create('shippings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('title')->nullable();
                $table->string('subtitle')->nullable();
                $table->decimal('price', 15, 4)->default(0);
            });
        }

        if (!Schema::hasTable('packages')) {
            Schema::create('packages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('title')->nullable();
                $table->string('subtitle')->nullable();
                $table->decimal('price', 15, 4)->default(0);
            });
        }

        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('vendor_id')->nullable();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->unsignedBigInteger('product_id')->nullable();
                $table->unsignedBigInteger('conversation_id')->nullable();
                $table->string('type')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('order_tracks')) {
            Schema::create('order_tracks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('order_id')->nullable();
                $table->string('title')->nullable();
                $table->text('text')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_tracks');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('shippings');
        Schema::dropIfExists('vendor_orders');
    }
};

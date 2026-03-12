<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->text('cart');
                $table->string('method')->nullable();
                $table->string('shipping')->nullable();
                $table->string('pickup_location')->nullable();
                $table->string('totalQty');
                $table->double('pay_amount');
                $table->string('txnid')->nullable();
                $table->string('charge_id')->nullable();
                $table->string('order_number');
                $table->string('payment_status')->default('Pending');
                $table->string('customer_email');
                $table->string('customer_name');
                $table->string('customer_country');
                $table->string('customer_phone');
                $table->string('customer_address')->nullable();
                $table->string('customer_city')->nullable();
                $table->string('customer_zip')->nullable();
                $table->string('shipping_name')->nullable();
                $table->string('shipping_country')->nullable();
                $table->string('shipping_email')->nullable();
                $table->string('shipping_phone')->nullable();
                $table->string('shipping_address')->nullable();
                $table->string('shipping_city')->nullable();
                $table->string('shipping_zip')->nullable();
                $table->text('order_note')->nullable();
                $table->string('coupon_code')->nullable();
                $table->string('coupon_discount')->nullable();
                $table->enum('status', ['pending', 'processing', 'completed', 'declined', 'on delivery'])->default('pending');
                $table->string('affilate_user')->nullable();
                $table->string('affilate_charge')->nullable();
                $table->string('currency_sign', 10);
                $table->string('currency_name', 10);
                $table->double('currency_value');
                $table->double('shipping_cost')->default(0);
                $table->double('packing_cost')->default(0);
                $table->double('tax');
                $table->string('tax_location')->nullable();
                $table->boolean('dp')->default(false);
                $table->text('pay_id')->nullable();
                $table->text('vendor_shipping_id')->nullable();
                $table->text('vendor_packing_id')->nullable();
                $table->string('vendor_ids')->nullable();
                $table->double('wallet_price')->default(0);
                $table->boolean('is_shipping')->default(true);
                $table->text('shipping_title')->nullable();
                $table->text('packing_title')->nullable();
                $table->string('customer_state')->nullable();
                $table->string('shipping_state')->nullable();
                $table->integer('discount')->default(0);
                $table->text('affilate_users')->nullable();
                $table->double('commission')->default(0);
                $table->text('riders')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

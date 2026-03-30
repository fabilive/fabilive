<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('user_subscriptions')) {
            Schema::create('user_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->nullable();
                $table->integer('subscription_id')->nullable();
                $table->string('title')->nullable();
                $table->string('currency_sign')->nullable();
                $table->string('currency_code')->nullable();
                $table->double('currency_value')->default(0);
                $table->double('price')->default(0);
                $table->integer('days')->default(0);
                $table->integer('allowed_products')->default(0);
                $table->text('details')->nullable();
                $table->string('method')->nullable();
                $table->string('txnid')->nullable();
                $table->string('charge_id')->nullable();
                $table->string('flutter_id')->nullable();
                $table->string('payment_number')->nullable();
                $table->integer('status')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('user_subscriptions');
    }
};

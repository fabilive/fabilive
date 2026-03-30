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
        if (!Schema::hasTable('subscriptions')) {
            Schema::create('subscriptions', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->string('currency')->default('XAF')->nullable();
                $table->string('currency_code')->default('XAF')->nullable();
                $table->double('price')->default(0);
                $table->integer('days')->default(0);
                $table->integer('allowed_products')->default(0);
                $table->text('details')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};

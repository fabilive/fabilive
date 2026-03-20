<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'product_city')) {
                $table->integer('product_city')->nullable();
            }
            if (!Schema::hasColumn('products', 'product_location')) {
                $table->integer('product_location')->nullable();
            }
            if (!Schema::hasColumn('products', 'delivery_fee')) {
                $table->double('delivery_fee')->nullable();
            }
            if (!Schema::hasColumn('products', 'delivery_unit')) {
                $table->string('delivery_unit')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['product_city', 'product_location', 'delivery_fee', 'delivery_unit']);
        });
    }
};

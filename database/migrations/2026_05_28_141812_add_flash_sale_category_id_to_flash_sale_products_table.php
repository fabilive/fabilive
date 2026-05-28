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
        Schema::table('flash_sale_products', function (Blueprint $table) {
            $table->unsignedBigInteger('flash_sale_category_id')->nullable()->after('time_slot_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flash_sale_products', function (Blueprint $table) {
            $table->dropColumn('flash_sale_category_id');
        });
    }
};

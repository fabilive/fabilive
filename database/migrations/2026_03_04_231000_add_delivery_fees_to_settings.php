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
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->decimal('delivery_base_fee', 15, 4)->default(1000);
            $table->decimal('delivery_stopover_fee', 15, 4)->default(300);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('generalsettings', function (Blueprint $table) {
            $table->dropColumn(['delivery_base_fee', 'delivery_stopover_fee']);
        });
    }
};

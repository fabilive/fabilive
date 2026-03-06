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
        if (Schema::hasTable('generalsettings')) {
            Schema::table('generalsettings', function (Blueprint $table) {
                if (!Schema::hasColumn('generalsettings', 'delivery_base_fee')) {
                    $table->decimal('delivery_base_fee', 15, 4)->default(1000);
                }
                if (!Schema::hasColumn('generalsettings', 'delivery_stopover_fee')) {
                    $table->decimal('delivery_stopover_fee', 15, 4)->default(300);
                }
                if (!Schema::hasColumn('generalsettings', 'rider_percentage_commission')) {
                    $table->decimal('rider_percentage_commission', 15, 4)->default(80);
                }
            });
        }
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

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
        if (Schema::hasTable('delivery_jobs')) {
            Schema::table('delivery_jobs', function (Blueprint $table) {
                if (!Schema::hasColumn('delivery_jobs', 'verified_at')) {
                    $table->timestamp('verified_at')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('delivery_jobs')) {
            if (Schema::hasColumn('delivery_jobs', 'verified_at')) {
                Schema::table('delivery_jobs', function (Blueprint $table) {
                    $table->dropColumn('verified_at');
                });
            }
        }
    }
};

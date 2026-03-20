<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('delivery_jobs')) {
            // Using raw SQL for compatibility with older Laravel/MariaDB environments without doctrine/dbal
            DB::statement("ALTER TABLE delivery_jobs MODIFY buyer_id INT(10) UNSIGNED NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('delivery_jobs')) {
            DB::statement("ALTER TABLE delivery_jobs MODIFY buyer_id INT(10) UNSIGNED NOT NULL");
        }
    }
};

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
        if (Schema::hasTable('riders')) {
            Schema::table('riders', function (Blueprint $table) {
                if (!Schema::hasColumn('riders', 'is_available')) {
                    $table->tinyInteger('is_available')->default(1)->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('riders')) {
            Schema::table('riders', function (Blueprint $table) {
                if (Schema::hasColumn('riders', 'is_available')) {
                    $table->dropColumn('is_available');
                }
            });
        }
    }
};

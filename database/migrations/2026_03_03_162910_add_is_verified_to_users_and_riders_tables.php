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
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'is_verified')) {
                    $table->boolean('is_verified')->default(false)->after('status');
                }
            });
        }

        if (Schema::hasTable('riders')) {
            Schema::table('riders', function (Blueprint $table) {
                if (!Schema::hasColumn('riders', 'is_verified')) {
                    $table->boolean('is_verified')->default(false)->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'is_verified')) {
                    $table->dropColumn('is_verified');
                }
            });
        }

        if (Schema::hasTable('riders')) {
            Schema::table('riders', function (Blueprint $table) {
                if (Schema::hasColumn('riders', 'is_verified')) {
                    $table->dropColumn('is_verified');
                }
            });
        }
    }
};

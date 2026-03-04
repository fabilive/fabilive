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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'admin_verified')) {
                $table->boolean('admin_verified')->default(false)->after('escrow_status');
            }
        });

        Schema::table('wallet_ledger', function (Blueprint $table) {
            $table->index(['user_id', 'type', 'reference']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('admin_verified');
        });

        Schema::table('wallet_ledger', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'type', 'reference']);
            $table->dropIndex(['created_at']);
        });
    }
};

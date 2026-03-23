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
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'admin_verified')) {
                    $table->boolean('admin_verified')->default(false);
                }
            });
        }

        if (Schema::hasTable('wallet_ledger')) {
            Schema::table('wallet_ledger', function (Blueprint $table) {
                $indexes = DB::select("SHOW INDEX FROM wallet_ledger");
                $indexNames = array_column($indexes, 'Key_name');

                if (!in_array('wallet_ledger_user_id_type_reference_index', $indexNames)) {
                    $table->index(['user_id', 'type', 'reference']);
                }
                if (!in_array('wallet_ledger_created_at_index', $indexNames)) {
                    $table->index('created_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            if (Schema::hasColumn('orders', 'admin_verified')) {
                Schema::table('orders', function (Blueprint $table) {
                    $table->dropColumn('admin_verified');
                });
            }
        }

        if (Schema::hasTable('wallet_ledger')) {
            Schema::table('wallet_ledger', function (Blueprint $table) {
                $indexes = DB::select("SHOW INDEX FROM wallet_ledger");
                $indexNames = array_column($indexes, 'Key_name');

                if (in_array('wallet_ledger_user_id_type_reference_index', $indexNames)) {
                    $table->dropIndex(['user_id', 'type', 'reference']);
                }
                if (in_array('wallet_ledger_created_at_index', $indexNames)) {
                    $table->dropIndex(['created_at']);
                }
            });
        }
    }
};

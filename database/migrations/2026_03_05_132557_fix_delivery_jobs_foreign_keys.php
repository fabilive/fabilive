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
        // 1. Drop existing foreign key referencing 'users' inside delivery_jobs
        if (Schema::hasTable('delivery_jobs')) {
            $fks = $this->getForeignKeys('delivery_jobs');
            if (in_array('delivery_jobs_assigned_rider_id_foreign', $fks)) {
                Schema::table('delivery_jobs', function (Blueprint $table) {
                    $table->dropForeign(['assigned_rider_id']);
                });
            }

            Schema::table('delivery_jobs', function (Blueprint $table) {
                $riderIdType = $this->getColumnType('riders', 'id');
                // If it's bigint(20) without unsigned, we must use bigInteger (signed)
                if (str_contains(strtolower($riderIdType), 'unsigned')) {
                    $table->bigInteger('assigned_rider_id')->unsigned()->nullable()->change();
                } else {
                    $table->bigInteger('assigned_rider_id')->nullable()->change(); // signed
                }
                
                $fks = $this->getForeignKeys('delivery_jobs');
                if (!in_array('delivery_jobs_assigned_rider_id_foreign', $fks)) {
                    $table->foreign('assigned_rider_id')->references('id')->on('riders')->onDelete('set null');
                }
            });
        }

        if (Schema::hasTable('delivery_chat_threads')) {
            Schema::table('delivery_chat_threads', function (Blueprint $table) {
                $riderIdType = $this->getColumnType('riders', 'id');
                if (str_contains(strtolower($riderIdType), 'unsigned')) {
                    $table->bigInteger('rider_id')->unsigned()->change();
                } else {
                    $table->bigInteger('rider_id')->change(); // signed
                }
                
                $fks = $this->getForeignKeys('delivery_chat_threads');
                if (!in_array('delivery_chat_threads_rider_id_foreign', $fks)) {
                    $table->foreign('rider_id')->references('id')->on('riders')->onDelete('cascade');
                }
            });
        }
    }

    protected function getForeignKeys($table)
    {
        $conn = Schema::getConnection();
        $db = $conn->getDatabaseName();
        $results = DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME != 'PRIMARY'", [$db, $table]);
        return array_column($results, 'CONSTRAINT_NAME');
    }

    protected function getColumnType($table, $column)
    {
        $conn = Schema::getConnection();
        $db = $conn->getDatabaseName();
        $result = DB::selectOne("SELECT COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?", [$db, $table, $column]);
        return $result ? $result->COLUMN_TYPE : '';
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op for safety in this fix
    }
};

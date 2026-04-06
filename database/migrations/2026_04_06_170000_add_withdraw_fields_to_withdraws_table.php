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
        Schema::table('withdraws', function (Blueprint $table) {
            if (!Schema::hasColumn('withdraws', 'network')) {
                $table->string('network')->nullable()->after('method');
            }
            if (!Schema::hasColumn('withdraws', 'campay_acc_no')) {
                $table->string('campay_acc_no')->nullable()->after('network');
            }
            if (!Schema::hasColumn('withdraws', 'campay_acc_name')) {
                $table->string('campay_acc_name')->nullable()->after('campay_acc_no');
            }
            if (!Schema::hasColumn('withdraws', 'iban')) {
                $table->string('iban')->nullable()->after('acc_number');
            }
            if (!Schema::hasColumn('withdraws', 'address')) {
                $table->text('address')->nullable()->after('acc_name');
            }
            if (!Schema::hasColumn('withdraws', 'swift')) {
                $table->string('swift')->nullable()->after('address');
            }
            if (!Schema::hasColumn('withdraws', 'reference')) {
                $table->text('reference')->nullable()->after('amount');
            }
            if (!Schema::hasColumn('withdraws', 'country')) {
                $table->string('country')->nullable()->after('acc_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdraws', function (Blueprint $table) {
            $table->dropColumn([
                'network',
                'campay_acc_no',
                'campay_acc_name',
                'iban',
                'address',
                'swift',
                'reference',
                'country'
            ]);
        });
    }
};

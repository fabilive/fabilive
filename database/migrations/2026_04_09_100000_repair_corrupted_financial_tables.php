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
        // 1. Repair Deposits
        $d_columns = Schema::getColumnListing('deposits');
        if (count($d_columns) === 0) {
            Schema::dropIfExists('deposits');
            Schema::create('deposits', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('currency')->nullable();
                $table->string('currency_code')->nullable();
                $table->decimal('currency_value', 15, 4)->default(1);
                $table->decimal('amount', 15, 4)->default(0);
                $table->string('method')->nullable();
                $table->string('txnid')->nullable();
                $table->integer('status')->default(0); // 0 = pending, 1 = completed
                $table->timestamps();
            });
        }

        // 2. Repair Transactions
        $t_columns = Schema::getColumnListing('transactions');
        if (count($t_columns) === 0) {
            Schema::dropIfExists('transactions');
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->decimal('amount', 15, 4)->default(0);
                $table->string('currency_sign')->nullable();
                $table->string('currency_code')->nullable();
                $table->decimal('currency_value', 15, 4)->default(1);
                $table->string('method')->nullable();
                $table->string('txnid')->nullable();
                $table->string('txn_number')->nullable();
                $table->text('details')->nullable();
                $table->string('type')->nullable(); // plus, minus
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down needed as this is a repair of corrupt state
    }
};

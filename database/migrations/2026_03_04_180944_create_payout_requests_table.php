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
        if (!Schema::hasTable('payout_requests')) {
            Schema::create('payout_requests', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('user_id');
                $table->string('role')->default('seller'); // seller, rider
                $table->decimal('amount', 15, 4);
                $table->string('method')->nullable();
                $table->string('destination')->nullable();
                $table->string('status')->default('pending'); // pending, approved, rejected
                $table->timestamp('admin_action_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_requests');
    }
};

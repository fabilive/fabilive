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
        // For temporary chats, we can either use existing chat tables or create a dedicated threads table.
        // Given the requirement to auto-hide and retain archive, a dedicated threads table is cleaner.
        Schema::create('delivery_chat_threads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delivery_job_id');
            $table->string('thread_type'); // rider_seller, rider_buyer
            $table->unsignedInteger('seller_id')->nullable();
            $table->unsignedInteger('buyer_id')->nullable();
            $table->unsignedInteger('rider_id');
            
            $table->timestamp('hidden_at')->nullable(); // Set when job is Delivered/Returned/Cancelled
            $table->timestamps();

            $table->foreign('delivery_job_id')->references('id')->on('delivery_jobs')->onDelete('cascade');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('delivery_chat_thread_id')->nullable()->after('conversation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('delivery_chat_thread_id');
        });
        Schema::dropIfExists('delivery_chat_threads');
    }
};

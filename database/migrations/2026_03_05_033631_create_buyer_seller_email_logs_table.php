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
        if (!Schema::hasTable('buyer_seller_email_logs')) {
            Schema::create('buyer_seller_email_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vendor_id');
                $table->string('buyer_email');
                $table->string('subject');
                $table->text('message');
                $table->timestamps();
                
                $table->index('vendor_id');
                $table->index('buyer_email');
                
                $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyer_seller_email_logs');
    }
};

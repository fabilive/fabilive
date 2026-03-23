<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_referrals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referrer_id')->index();
            $table->unsignedBigInteger('referred_id')->index();
            $table->decimal('amount', 11, 2)->default(500);
            $table->enum('status', ['locked', 'unlocked', 'expired'])->default('locked');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Note: Fabilive users table might be MyISAM or have varying ID lengths, so we just use standard references if possible 
            // but typical to not have strict foreign key constraints in such old structures unless consistent.
            // We omit strict constraints to prevent migration errors, data is managed by code.
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_referrals');
    }
};

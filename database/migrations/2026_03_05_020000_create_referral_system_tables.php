<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('referral_codes')) {
            Schema::create('referral_codes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('rider_id')->nullable();
                $table->string('code', 20)->unique();
                $table->enum('owner_role', ['buyer', 'seller', 'rider'])->default('buyer');
                $table->unsignedInteger('usages_count')->default(0);
                $table->unsignedInteger('max_usages')->default(100);
                $table->boolean('active')->default(true);
                $table->timestamps();

                $table->index('user_id');
                $table->index('rider_id');
            });
        }

        if (!Schema::hasTable('referral_usages')) {
            Schema::create('referral_usages', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('referral_code_id');
                $table->unsignedBigInteger('referred_user_id')->nullable();
                $table->unsignedBigInteger('referred_rider_id')->nullable();
                $table->enum('referred_role', ['buyer', 'seller', 'rider']);
                $table->decimal('referrer_bonus', 10, 2)->default(0);
                $table->decimal('referred_bonus', 10, 2)->default(0);
                $table->enum('status', ['pending', 'awarded', 'rejected'])->default('pending');
                $table->string('phone_hash', 64)->nullable();
                $table->string('email_hash', 64)->nullable();
                $table->timestamps();

                $table->foreign('referral_code_id')->references('id')->on('referral_codes')->onDelete('cascade');
                $table->index('referred_user_id');
                $table->index('referred_rider_id');
                $table->index('phone_hash');
                $table->index('email_hash');
            });
        }

        // Add referral settings to generalsettings
        if (Schema::hasTable('generalsettings') && !Schema::hasColumn('generalsettings', 'referral_bonus_referrer')) {
            Schema::table('generalsettings', function (Blueprint $table) {
                $table->decimal('referral_bonus_referrer', 10, 2)->default(500);
                $table->decimal('referral_bonus_referred', 10, 2)->default(250);
                $table->boolean('referral_system_active')->default(true);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_usages');
        Schema::dropIfExists('referral_codes');

        if (Schema::hasColumn('generalsettings', 'referral_bonus_referrer')) {
            Schema::table('generalsettings', function (Blueprint $table) {
                $table->dropColumn(['referral_bonus_referrer', 'referral_bonus_referred', 'referral_system_active']);
            });
        }
    }
};

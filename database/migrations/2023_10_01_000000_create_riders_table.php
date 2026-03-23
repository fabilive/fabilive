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
        if (!Schema::hasTable('riders')) {
            Schema::create('riders', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('email')->unique();
                $table->string('password');
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->unsignedBigInteger('city_id')->nullable();
                $table->unsignedBigInteger('state_id')->nullable();
                $table->string('zip')->nullable();
                $table->string('country')->nullable();
                $table->string('photo')->nullable();
                $table->string('fax')->nullable();
                $table->string('location')->nullable();
                $table->string('email_verify')->default('No');
                $table->string('email_verified')->default('No');
                $table->string('email_token')->nullable();
                $table->integer('status')->default(1);
                $table->decimal('balance', 15, 4)->default(0);
                $table->string('national_id_front_image')->nullable();
                $table->string('national_id_back_image')->nullable();
                $table->string('license_image')->nullable();
                $table->string('submerchant_agreement')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riders');
    }
};

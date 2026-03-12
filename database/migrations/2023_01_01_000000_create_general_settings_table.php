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
        if (!Schema::hasTable('generalsettings')) {
            Schema::create('generalsettings', function (Blueprint $table) {
                $table->id();
                $table->string('logo')->nullable();
                $table->string('favicon')->nullable();
                $table->string('title')->nullable();
                $table->string('header_email')->nullable();
                $table->string('header_phone')->nullable();
                $table->string('footer')->nullable();
                $table->string('copyright')->nullable();
                $table->tinyInteger('is_capcha')->default(0);
                $table->string('capcha_site_key')->nullable();
                $table->string('capcha_secret_key')->nullable();
                $table->decimal('referral_amount', 15, 4)->default(0);
                $table->decimal('referral_bonus', 15, 4)->default(0);
                $table->string('from_email')->nullable();
                $table->string('from_name')->nullable();
                $table->tinyInteger('is_smtp')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('generalsettings');
    }
};

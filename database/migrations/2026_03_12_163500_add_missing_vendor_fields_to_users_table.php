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
        Schema::table('users', function (Blueprint $blueprint) {
            if (!Schema::hasColumn('users', 'selfie_image')) {
                $blueprint->string('selfie_image')->nullable();
            }
            if (!Schema::hasColumn('users', 'submerchant_agreement')) {
                $blueprint->string('submerchant_agreement')->nullable();
            }
            if (!Schema::hasColumn('users', 'business_registration_certificate')) {
                $blueprint->string('business_registration_certificate')->nullable();
            }
            if (!Schema::hasColumn('users', 'taxpayer_card_copy')) {
                $blueprint->string('taxpayer_card_copy')->nullable();
            }
            if (!Schema::hasColumn('users', 'id_card_copy')) {
                $blueprint->string('id_card_copy')->nullable();
            }
            if (!Schema::hasColumn('users', 'passport_copy')) {
                $blueprint->string('passport_copy')->nullable();
            }
            if (!Schema::hasColumn('users', 'driver_license_copy')) {
                $blueprint->string('driver_license_copy')->nullable();
            }
            if (!Schema::hasColumn('users', 'residence_permit')) {
                $blueprint->string('residence_permit')->nullable();
            }
            if (!Schema::hasColumn('users', 'reff')) {
                $blueprint->string('reff')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $blueprint) {
            $blueprint->dropColumn([
                'selfie_image',
                'submerchant_agreement',
                'business_registration_certificate',
                'taxpayer_card_copy',
                'id_card_copy',
                'passport_copy',
                'driver_license_copy',
                'residence_permit',
                'reff'
            ]);
        });
    }
};

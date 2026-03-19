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
        $columns = [
            'rider_type', 'company_registration_document', 'id_company_owner', 'live_selfie_company',
            'transport_license', 'insurance_certificate_company', 'tin_company', 'rider_status',
            'vehicle_type_individual', 'tin_individual', 'driver_license_individual',
            'live_selfie_individual', 'vehicle_registration_certificate',
            'insurance_certificate_individual', 'criminal_records',
            'national_id_front_image', 'national_id_back_image', 'license_image', 'submerchant_agreement'
        ];

        foreach ($columns as $column) {
            if (!Schema::hasColumn('riders', $column)) {
                Schema::table('riders', function (Blueprint $table) use ($column) {
                    $table->text($column)->nullable();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riders', function (Blueprint $table) {
            //
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RenameCityIdToServiceAreaIdInRiderServiceAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('rider_service_areas', 'city_id')) {
            Schema::table('rider_service_areas', function (Blueprint $table) {
                $table->renameColumn('city_id', 'service_area_id');
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
        if (Schema::hasColumn('rider_service_areas', 'service_area_id')) {
            Schema::table('rider_service_areas', function (Blueprint $table) {
                $table->renameColumn('service_area_id', 'city_id');
            });
        }
    }
}

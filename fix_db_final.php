<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

if (!Schema::hasColumn('generalsettings', 'rider_percentage_commission')) {
    Schema::table('generalsettings', function (Blueprint $table) {
        $table->decimal('rider_percentage_commission', 15, 4)->default(80);
    });
    echo "Column rider_percentage_commission Added\n";
}

if (!Schema::hasColumn('service_areas', 'base_fee')) {
    Schema::table('service_areas', function (Blueprint $table) {
        $table->decimal('base_fee', 15, 4)->default(0);
        $table->decimal('stopover_fee', 15, 4)->default(0);
    });
    echo "ServiceArea columns Added\n";
}

if (!Schema::hasColumn('riders', 'rider_status')) {
    Schema::table('riders', function (Blueprint $table) {
        $table->string('rider_status')->default('pending');
        $table->boolean('is_verified')->default(false);
    });
    echo "Rider columns Added\n";
}

try {
    Artisan::call('db:seed', ['--force' => true]);
    echo "Seeding Completed\n";
    echo Artisan::output();
} catch (\Exception $e) {
    echo "Seeding Error: " . $e->getMessage() . "\n";
}

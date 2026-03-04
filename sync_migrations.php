<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$files = [
    '2014_10_12_000000_create_users_table',
    '2014_10_12_100000_create_password_reset_tokens_table',
    '2019_08_19_000000_create_failed_jobs_table',
    '2019_12_14_000001_create_personal_access_tokens_table',
    '2023_10_03_075357_create_cities_table',
    '2023_10_17_081021_create_delivery_riders_table',
    '2023_10_17_092331_create_rider_service_areas_table',
    '2023_10_18_031949_create_pickup_points_table'
];

foreach ($files as $f) {
    if (DB::table('migrations')->where('migration', $f)->count() == 0) {
        DB::table('migrations')->insert(['migration' => $f, 'batch' => 1]);
        echo 'Synced ' . $f . "\n";
    } else {
        echo 'Already synced ' . $f . "\n";
    }
}

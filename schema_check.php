<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use Illuminate\Support\Facades\Schema;

$tables = ['users', 'riders'];
$result = [];

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        $result[$table] = Schema::getColumnListing($table);
    } else {
        $result[$table] = 'Table not found';
    }
}

echo json_encode($result, JSON_PRETTY_PRINT);

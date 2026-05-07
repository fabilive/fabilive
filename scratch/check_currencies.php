<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
try {
    $currencies = DB::table('currencies')->get();
    echo json_encode($currencies);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

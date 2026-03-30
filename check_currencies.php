<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$currencies = DB::table('currencies')->get();
echo json_encode($currencies, JSON_PRETTY_PRINT);

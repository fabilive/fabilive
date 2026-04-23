<?php
putenv('DB_PORT=3307');
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$currencies = App\Models\Currency::all();
foreach ($currencies as $curr) {
    echo "ID: {$curr->id}, Name: {$curr->name}, Sign: {$curr->sign}, Value: {$curr->value}, Default: {$curr->is_default}\n";
}

<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$cols = DB::getSchemaBuilder()->getColumnListing('users');
echo json_encode($cols);

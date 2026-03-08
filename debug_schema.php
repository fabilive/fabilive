<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$columns = \Schema::getColumnListing('service_areas');
echo "Columns in service_areas: " . implode(", ", $columns) . "\n";

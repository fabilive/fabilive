<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use App\Models\ServiceArea;

$columns = Schema::getColumnListing('service_areas');
$data = ServiceArea::all();

echo json_encode([
    'columns' => $columns,
    'data' => $data
], JSON_PRETTY_PRINT);

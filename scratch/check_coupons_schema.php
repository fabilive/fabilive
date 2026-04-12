<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    $columns = DB::select('DESCRIBE coupons');
    foreach($columns as $col){
        echo "Field: " . $col->Field . " | Type: " . $col->Type . " | Null: " . $col->Null . "\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}

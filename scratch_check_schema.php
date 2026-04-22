<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

$type = Schema::getColumnType('orders', 'coupon_id');
echo "Column Type: " . $type . "\n";

$schema = DB::select("DESCRIBE orders");
foreach($schema as $col) {
    if($col->Field == 'coupon_id') {
        print_r($col);
    }
}

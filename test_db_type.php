<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$res = DB::select("DESCRIBE users");
foreach ($res as $col) {
    if ($col->Field === 'id') {
        echo "users.id is " . $col->Type . "\n";
    }
}
$res = DB::select("DESCRIBE orders");
foreach ($res as $col) {
    if ($col->Field === 'id') {
        echo "orders.id is " . $col->Type . "\n";
    }
}

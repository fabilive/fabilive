<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
use Illuminate\Support\Facades\DB;
$r = DB::select("DESCRIBE users");
foreach($r as $c){if($c->Field==='id'){echo "users.id=".$c->Type."\n";}}
$r2 = DB::select("DESCRIBE orders");
foreach($r2 as $c){if($c->Field==='id'){echo "orders.id=".$c->Type."\n";}}

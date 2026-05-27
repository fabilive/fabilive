<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$gs = DB::table('generalsettings')->first();
echo "multiple_shipping: " . $gs->multiple_shipping . "\n";
echo "delivery_base_fee: " . ($gs->delivery_base_fee ?? 'null') . "\n";
echo "delivery_stopover_fee: " . ($gs->delivery_stopover_fee ?? 'null') . "\n";

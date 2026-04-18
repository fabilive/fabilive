<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$gs = \App\Models\Generalsetting::findOrFail(1);
$prod = \App\Models\Product::find(137);

$data = [
    'fixed_commission' => $gs->fixed_commission,
    'percentage_commission' => $gs->percentage_commission,
    'product_raw_price' => $prod->price, // This will show the accessor value!!
    'product_db_price' => $prod->getRawOriginal('price'),
    'product_user_id' => $prod->user_id
];

file_put_contents('scratch/pricing_debug.json', json_encode($data, JSON_PRETTY_PRINT));
echo "Debug data written to scratch/pricing_debug.json\n";

<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Generalsetting;

$gs = Generalsetting::first();
$p = Product::where('name', 'Shille Fabile')->first();

if ($p) {
    echo json_encode([
        'gs_commission' => $gs->is_commission,
        'gs_fixed' => $gs->fixed_commission,
        'gs_percentage' => $gs->percentage_commission,
        'prod_raw_price' => $p->getAttributes()['price'],
        'prod_accessor_price' => $p->price
    ], JSON_PRETTY_PRINT);
} else {
    echo "Product not found";
}

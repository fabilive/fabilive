<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;
use App\Models\Generalsetting;

$prods = Product::whereIn('name', ['Shille Fabile', 'Fabi gab', 'testing servers', 'Support System56'])->get();

foreach ($prods as $p) {
    echo "Name: " . $p->name . "\n";
    echo "Raw Price: " . $p->getAttributes()['price'] . "\n";
    echo "Raw Previous Price: " . ($p->getAttributes()['previous_price'] ?? 'N/A') . "\n";
    echo "Active Price (accessor): " . $p->price . "\n";
    echo "User ID: " . $p->user_id . "\n";
    echo "-------------------\n";
}

$gs = Generalsetting::first();
echo "GS is_commission: " . $gs->is_commission . "\n";
echo "GS commission_amount: " . ($gs->percentage_commission ?? 'N/A') . "\n";
echo "GS fixed_commission: " . ($gs->fixed_commission ?? 'N/A') . "\n";

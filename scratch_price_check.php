<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$gs = \App\Models\Generalsetting::find(1);
$p = \App\Models\Product::where('name', 'New shoe')->first();

echo "General Settings:\n";
echo "Fixed Commission: " . ($gs->fixed_commission ?? 'NULL') . "\n";
echo "Percentage Commission: " . ($gs->percentage_commission ?? 'NULL') . "\n";

if ($p) {
    echo "\nProduct Details:\n";
    echo "Raw DB Price: " . $p->getRawOriginal('price') . "\n";
    echo "Raw DB Previous Price: " . $p->getRawOriginal('previous_price') . "\n";
    echo "Calculated Product->price: " . $p->price . "\n";
    echo "Product->vendorPrice(): " . $p->vendorPrice() . "\n";
    echo "User ID: " . $p->user_id . "\n";
} else {
    echo "\nProduct 'New shoe' not found.\n";
}

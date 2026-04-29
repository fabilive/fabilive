<?php

// Mocking required classes/functions if needed, but since we are running in the app context via a script, we can just use the model.
// However, to run it easily, I'll just check the logic in a standalone way if possible or use artisan tinker.

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

$prices = [500, 1000, 5000, 10000, 10001, 20000, 30000, 30001, 40000, 50000, 50001, 75000, 100000, 100001, 200000];

echo "Testing Commission Tiers:\n";
echo "--------------------------\n";
foreach ($prices as $price) {
    $commission = Product::getTieredCommission($price);
    echo "Price: " . number_format($price) . " XAF -> Commission: " . number_format($commission) . " XAF\n";
}

echo "\nTesting referral discount in CouponController logic simulation:\n";
$gs = \App\Models\Generalsetting::safeFirst();
echo "Referral Amount (Buyer Discount): " . ($gs->referral_amount ?? 500) . "\n";
echo "Referral Bonus (Referrer Reward): " . ($gs->referral_bonus ?? 150) . "\n";

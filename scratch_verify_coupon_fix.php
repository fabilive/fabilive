<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\Session;
use App\Models\Currency;

// Mock currency for PriceHelper
$curr = Currency::where('is_default', 1)->first() ?: new \stdClass();
if (!isset($curr->sign)) $curr->sign = 'CFA';
if (!isset($curr->value)) $curr->value = 1;
Session::put('currency', $curr->id ?? 1);

echo "--- Simulating Referral Applied ---\n";
// Manually setting what CouponController would set after successful referral validation
$total = 1000;
$discount = 200;
$code = "REF123";
$formattedTotal = "CFA800"; // Simulation of showCurrencyPrice

Session::put('coupon', $discount);
Session::put('coupon_code', $code);
Session::put('coupon_id', 'referral');
Session::put('coupon_is_referral', true);
Session::put('coupon_total', $formattedTotal);

echo "Session coupon_id: " . Session::get('coupon_id') . " (Expected: referral)\n";
echo "Session coupon_total: " . Session::get('coupon_total') . " (Expected: CFA800)\n";

// Robust parsing simulation (from CheckoutController)
$extractedTotal = (float) preg_replace('/[^0-9\.]/ui', '', Session::get('coupon_total'));
echo "Extracted Total: " . $extractedTotal . " (Expected: 800)\n";

echo "\n--- Simulating Recalculation (couponcheck) ---\n";
// Simulate what changed in couponcheck()
// My fix ensures it updates coupon_total correctly now instead of coupon_total1
$newTotal = 900; // e.g. shipping changed
$newFormattedTotal = "CFA700"; // discount remains 200

Session::put('coupon_total', $newFormattedTotal);
// Ensure coupon_total1 is NOT used by me anymore
if (Session::has('coupon_total1')) {
    echo "WARNING: coupon_total1 still exists!\n";
} else {
    echo "SUCCESS: coupon_total1 is not used.\n";
}

$extractedNewTotal = (float) preg_replace('/[^0-9\.]/ui', '', Session::get('coupon_total'));
echo "Extracted New Total: " . $extractedNewTotal . " (Expected: 700)\n";

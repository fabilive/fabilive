<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Coupon;
use App\Models\Product;

echo "--- Coupon Multi-Category Validation Test ---\n";

// Test Case 1: Multi-category check
$coupon = new Coupon();
$coupon->coupon_type = 'category';
$coupon->category = '1,2,3'; // Men, Women, Kids

$testProducts = [
    ['id' => 101, 'cat_id' => 1, 'name' => 'Men Shirt', 'expected' => true],
    ['id' => 102, 'cat_id' => 4, 'name' => 'Electronics', 'expected' => false],
];

foreach($testProducts as $tp){
    $cats = explode(',', $coupon->category);
    $result = in_array($tp['cat_id'], $cats);
    echo "Product: {$tp['name']} (Cat: {$tp['cat_id']}) | Match: " . ($result ? "YES" : "NO") . " | Status: " . ($result == $tp['expected'] ? "PASS" : "FAIL") . "\n";
}

// Test Case 2: 'all' keyword check
$couponAll = new Coupon();
$couponAll->coupon_type = 'category';
$couponAll->category = 'all';

$resultAll = in_array('all', explode(',', $couponAll->category));
echo "Testing 'all' keyword... Match: " . ($resultAll ? "YES" : "NO") . " | Status: " . ($resultAll == true ? "PASS" : "FAIL") . "\n";

echo "--- Test Complete ---\n";

<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Coupon;
use App\Models\ReferralCode;
use App\Models\User;

echo "--- Standard Coupons ---\n";
$coupons = Coupon::all();
foreach ($coupons as $c) {
    echo "ID: {$c->id}, Code: {$c->code}, Status: {$c->status}, Type: {$c->type}, Price: {$c->price}\n";
}

echo "\n--- Referral Codes ---\n";
$refs = ReferralCode::all();
foreach ($refs as $r) {
    echo "ID: {$r->id}, Code: {$r->code}, Owner: " . ($r->user_id ?? 'N/A') . " ({$r->owner_role})\n";
}

echo "\n--- Recent Users ---\n";
$users = User::orderBy('id', 'desc')->limit(5)->get();
foreach ($users as $u) {
    $orderCount = \App\Models\Order::where('user_id', $u->id)->count();
    echo "ID: {$u->id}, Email: {$u->email}, Orders: {$orderCount}\n";
}

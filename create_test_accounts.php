<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Rider;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

echo "--- STARTING ACCOUNT CREATION ---\n";

// 1. Buyer
$buyer = User::where('email', 'buyer@fabilive.test')->first();
if (!$buyer) {
    $buyer = new User();
    $buyer->name = 'Test Buyer';
    $buyer->email = 'buyer@fabilive.test';
}
$buyer->password = Hash::make('password');
$buyer->email_verified = 'Yes';
$buyer->is_vendor = 0;
$buyer->save();
echo "Buyer account created/updated: buyer@fabilive.test\n";

// 2. Seller (Vendor)
$seller = User::where('email', 'seller1@fabilive.test')->first();
if (!$seller) {
    $seller = new User();
    $seller->name = 'Test Seller';
    $seller->email = 'seller1@fabilive.test';
}
$seller->password = Hash::make('password');
$seller->email_verified = 'Yes';
$seller->is_vendor = 2; // Typically 2 for active vendor
$seller->save();
echo "Seller account created/updated: seller1@fabilive.test\n";

// 3. Rider
$rider = Rider::where('email', 'rider@fabilive.test')->first();
if (!$rider) {
    $rider = new Rider();
    $rider->name = 'Test Rider';
    $rider->email = 'rider@fabilive.test';
}
$rider->password = Hash::make('password');
$rider->status = 1;
$rider->save();
echo "Rider account created/updated: rider@fabilive.test\n";

// 4. Admin (Ensure password is 'password')
$admin = Admin::where('email', 'admin@fabilive.test')->first();
if ($admin) {
    $admin->password = Hash::make('password');
    $admin->save();
    echo "Admin password reset: admin@fabilive.test\n";
}

echo "--- DONE ---\n";

<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'shilleybello@gmail.com')->first(['id', 'email', 'is_vendor']);
echo "User is_vendor: " . ($user ? $user->is_vendor : 'Not found') . "\n";
$all = App\Models\User::count();
$vendors = App\Models\User::where('is_vendor', 2)->count();
echo "Total users: $all, vendors: $vendors\n";

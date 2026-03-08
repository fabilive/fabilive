<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Users: " . \App\Models\User::count() . "\n";
echo "Products: " . \App\Models\Product::count() . "\n";
echo "Admins: " . \App\Models\Admin::count() . "\n";

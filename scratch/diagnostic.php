<?php
include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$gs = \DB::table('generalsettings')->first();
$prod = \DB::table('products')->where('id', 137)->first();

echo "--- SETTINGS ---\n";
echo "Fixed Commission: " . ($gs->fixed_commission ?? 'N/A') . "\n";
echo "Percentage Commission: " . ($gs->percentage_commission ?? 'N/A') . "\n";
echo "\n--- PRODUCT (ID 137) ---\n";
echo "Raw Price: " . ($prod->price ?? 'N/A') . "\n";
echo "Previous Price: " . ($prod->previous_price ?? 'N/A') . "\n";
echo "User ID: " . ($prod->user_id ?? 'N/A') . "\n";

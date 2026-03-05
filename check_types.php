<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Wallet Ledger Table:\n";
try {
    print_r(DB::select('DESCRIBE wallet_ledger'));
} catch (\Exception $e) {
    echo "Table NOT found or error: " . $e->getMessage() . "\n";
}

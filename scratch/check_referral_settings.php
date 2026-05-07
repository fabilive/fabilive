<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
try {
    $gs = DB::table('generalsettings')->first();
    echo "referral_amount: " . ($gs->referral_amount ?? 'NULL') . "\n";
    echo "referral_bonus: " . ($gs->referral_bonus ?? 'NULL') . "\n";
    $curr = DB::table('currencies')->where('is_default', 1)->first();
    echo "default_curr_value: " . ($curr->value ?? 'NULL') . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

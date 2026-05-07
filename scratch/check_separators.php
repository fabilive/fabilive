<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Models\Generalsetting;

$gs = Generalsetting::first();
echo "Decimal Separator: '" . ($gs->decimal_separator) . "'\n";
echo "Thousand Separator: '" . ($gs->thousand_separator) . "'\n";
echo "Referral Amount: " . ($gs->referral_amount) . "\n";

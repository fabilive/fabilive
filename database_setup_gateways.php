<?php
/**
 * One-time setup script to manage payment gateways.
 * 1. Disable Stripe
 * 2. Enable/Add Cash on Delivery (COD)
 */

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\DB;

try {
    // 1. Disable Stripe
    $stripe = PaymentGateway::where('keyword', 'stripe')->first();
    if ($stripe) {
        $stripe->checkout = 0;
        $stripe->save();
        echo "Stripe has been disabled for checkout.\n";
    } else {
        echo "Stripe gateway not found.\n";
    }

    // 2. Enable or Create Cash on Delivery (COD)
    $cod = PaymentGateway::where('keyword', 'cod')->first();
    if ($cod) {
        $cod->checkout = 1;
        $cod->title = "Payment on Delivery";
        $cod->name = "Payment on Delivery";
        $cod->save();
        echo "Payment on Delivery (COD) has been enabled.\n";
    } else {
        // Create it if it doesn't exist
        PaymentGateway::create([
            'title' => 'Payment on Delivery',
            'details' => 'Pay when you receive your items.',
            'subtitle' => 'Cash/Card on Delivery',
            'name' => 'Payment on Delivery',
            'type' => 'manual',
            'keyword' => 'cod',
            'checkout' => 1,
            'currency_id' => '["1"]' // Default to first currency, usually works
        ]);
        echo "Payment on Delivery (COD) has been created and enabled.\n";
    }

    echo "\nSetup completed successfully!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

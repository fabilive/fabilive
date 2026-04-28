<?php

use App\Models\Order;
use App\Services\FabiliveNotifier;
use Illuminate\Support\Facades\Log;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testing Fabilive Notification System...\n";

$order = Order::latest()->first();

if (!$order) {
    echo "No orders found to test with.\n";
    exit;
}

echo "Testing Order Placed for Order #{$order->order_number}...\n";
try {
    FabiliveNotifier::orderPlaced($order);
    echo "Success: Notifications sent.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Testing Rider Assigned (Dummy ID 1)...\n";
try {
    FabiliveNotifier::riderAssigned($order, 1);
    echo "Success: Notifications sent.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "Check logs for mail errors if SMTP is not configured.\n";
echo "Check user_notifications table for new records.\n";

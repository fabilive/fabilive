<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;

$order = Order::where('txnid', '1ae08330-748b-4374-a331-7dcf66c7469a')->first();
if ($order) {
    echo "Order Found: " . $order->order_number . "\n";
    echo "Cart Type: " . gettype($order->cart) . "\n";
    echo "Cart Content (first 100 chars): " . substr($order->cart, 0, 100) . "\n";
    $decoded = json_decode($order->cart, true);
    echo "Decoded Type: " . gettype($decoded) . "\n";
    echo "Has items: " . (isset($decoded['items']) ? 'Yes' : 'No') . "\n";
    if (isset($decoded['items'])) {
        echo "Items Count: " . count($decoded['items']) . "\n";
        echo "Items Structure: " . print_r(array_keys($decoded['items']), true) . "\n";
    }
} else {
    echo "Order NOT found\n";
}

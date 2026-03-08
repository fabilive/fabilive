<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $order = App\Models\Order::where('order_number', 'haafYCZ9bi')->first();
    if (!$order) {
        die("Order haafYCZ9bi not found");
    }
    
    $user = App\Models\User::find($order->user_id);
    Auth::login($user); // Authenticate to fix IsVendor() auth null error
    
    $cart = json_decode($order->cart, true);
    
    echo "Rendering view for order " . $order->id . " (user " . $user->id . ")\n";
    $html = view('user.order.details', compact('user', 'order', 'cart'))->render();
    echo "Successfully rendered view.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
}

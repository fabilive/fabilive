<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $order = App\Models\Order::where('order_number', 'haafYCZ9bi')->first();
    if (!$order) die("Order not found");
    
    $user = App\Models\User::find($order->user_id);
    Auth::login($user);
    
    $request = Illuminate\Http\Request::create(route('user-order', $order->id), 'GET');
    $response = app()->handle($request);
    
    echo "Status Code: " . $response->getStatusCode() . "\n";
    if ($response->isRedirect()) {
        echo "Redirected to: " . $response->headers->get('Location') . "\n";
        
        // Let's check session errors since redirects usually have them
        $errors = session('errors');
        if ($errors) echo "Session Errors: " . json_encode($errors->all()) . "\n";
        
        $error = session('error');
        if ($error) echo "Session Error: " . $error . "\n";
        
        $unauth = session('auth-modal');
        if ($unauth) echo "Auth Modal: " . $unauth . "\n";
    } else {
        echo "Response length: " . strlen($response->getContent()) . "\n";
    }
} catch (\Exception $e) {
    echo "CRASH: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
}

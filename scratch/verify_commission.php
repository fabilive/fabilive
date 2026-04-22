<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Product;

$product = Product::where('user_id', '!=', 0)->first();
if ($product) {
    echo "Product ID: " . $product->id . "\n";
    echo "Raw DB Price: " . $product->getRawOriginal('price') . "\n";
    echo "Price Attribute (Accessor): " . $product->price . "\n";
    
    if ($product->getRawOriginal('price') == $product->price) {
        echo "SUCCESS: No markup applied.\n";
    } else {
        echo "FAILURE: Markup still exists!\n";
    }
} else {
    echo "No vendor product found to test.\n";
}

<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$product = App\Models\Product::latest('updated_at')->first(['id', 'name', 'price', 'previous_price', 'updated_at']);
if ($product) {
    echo "ID: " . $product->id . "\n";
    echo "Name: " . $product->name . "\n";
    echo "Price (raw): " . $product->getAttributes()['price'] . "\n";
    echo "Price (accessor): " . $product->price . "\n";
    echo "Previous Price: " . $product->previous_price . "\n";
    echo "Updated At: " . $product->updated_at . "\n";
} else {
    echo "No product found.\n";
}

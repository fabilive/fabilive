<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$product = App\Models\Product::find(13);
if ($product) {
    echo "ID: " . $product->id . "\n";
    echo "Name: " . $product->name . "\n";
    echo "Raw Price: " . $product->getAttributes()['price'] . "\n";
    echo "Accessor Price: " . $product->price . "\n";
    echo "Size Price: " . json_encode($product->size_price) . "\n";
    echo "Show Price: " . $product->showPrice() . "\n";
} else {
    echo "Product 13 not found.\n";
}

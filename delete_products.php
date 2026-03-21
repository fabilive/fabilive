<?php
use App\Models\Product;

$count = Product::count();
Product::query()->delete();
echo "Deleted $count products successfully.";

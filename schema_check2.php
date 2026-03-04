<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;

echo "Products: " . Product::count() . "\n";
echo "Active Products: " . Product::where('status', 1)->count() . "\n";
echo "Categories: " . Category::count() . "\n";
echo "Subcategories: " . Subcategory::count() . "\n";

$cat = Category::where('slug', 'A-labore-vitae-minim')->first();
if ($cat) {
    echo "Category 'A-labore-vitae-minim' ID: " . $cat->id . "\n";
    $sub = Subcategory::where('slug', 'wigs')->where('category_id', $cat->id)->first();
    if ($sub) {
        echo "Subcategory 'wigs' ID: " . $sub->id . "\n";
        echo "Products in this subcat: " . Product::where('subcategory_id', $sub->id)->count() . "\n";
    } else {
        echo "Subcategory 'wigs' NOT FOUND in this category\n";
    }
} else {
    echo "Category 'A-labore-vitae-minim' NOT FOUND\n";
}

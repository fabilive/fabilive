<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\Generalsetting;

try {
    $columns = Schema::getColumnListing('generalsettings');
    echo "Generalsetting Columns: " . implode(', ', $columns) . "\n\n";

    $gs = Generalsetting::first();
    echo "General Settings:\n";
    echo "is_commission present? " . (in_array('is_commission', $columns) ? 'Yes' : 'No') . "\n";
    echo "fixed_commission: " . $gs->fixed_commission . "\n";
    echo "percentage_commission: " . $gs->percentage_commission . "\n\n";

    $p = Product::where('name', 'Shille Fabile')->first();
    if ($p) {
        $raw = $p->getAttributes();
        echo "Product: Shille Fabile\n";
        echo "Raw Price: " . $raw['price'] . "\n";
        echo "Raw Previous Price: " . ($raw['previous_price'] ?? 'N/A') . "\n";
        echo "Accessor Price: " . $p->price . "\n";
        echo "User ID: " . $p->user_id . "\n";
    } else {
        echo "Product 'Shille Fabile' not found.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

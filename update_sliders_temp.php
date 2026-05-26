<?php
define('LARAVEL_START', microtime(true));

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Try updating sliders
try {
    $affected = DB::table('sliders')
        ->where('title_text', 'Premium Electronics')
        ->orWhere('title_text', 'Latest Electronics')
        ->update([
            'title_text' => 'Premium Products',
            'details_text' => 'Up to 50% OFF on all Products'
        ]);

    DB::table('sliders')
        ->where('details_text', 'like', '%all electronics%')
        ->update([
            'details_text' => 'Up to 50% OFF on all Products'
        ]);

    echo "Updated sliders count: " . $affected . "\n";
} catch (\Exception $e) {
    echo "Update failed: " . $e->getMessage() . "\n";
}


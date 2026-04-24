<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

try {
    $columns = Schema::getColumnListing('referral_codes');
    print_r($columns);
    
    $count = DB::table('referral_codes')->count();
    echo "\nTotal referral codes: $count\n";
    
    if ($count > 0) {
        $first = DB::table('referral_codes')->first();
        print_r($first);
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}

<?php
require __DIR__ . '/vendor/autoload.php';
use Carbon\Carbon;

try {
    $date = Carbon::parse('04/10/2026')->format('Y-m-d');
    echo "Parsed: " . $date . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

<?php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting manual cache clear...\n";

$paths = [
    __DIR__ . '/../bootstrap/cache/config.php',
    __DIR__ . '/../bootstrap/cache/routes.php',
    __DIR__ . '/../bootstrap/cache/services.php',
    __DIR__ . '/../bootstrap/cache/packages.php',
    __DIR__ . '/../storage/framework/views/*.php',
];

foreach ($paths as $path) {
    if (strpos($path, '*') !== false) {
        foreach (glob($path) as $file) {
            if (unlink($file)) {
                echo "Deleted: $file\n";
            }
        }
    } else {
        if (file_exists($path)) {
            if (unlink($path)) {
                echo "Deleted: $path\n";
            } else {
                echo "Failed to delete: $path\n";
            }
        } else {
            echo "Not found: $path\n";
        }
    }
}

echo "Manual clear finished.\n";

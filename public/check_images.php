<?php
header('Content-Type: text/plain');
$files = [
    'market.png',
    'baby.png',
    'health.png',
    'gaming.png',
    'building.png',
    '1568878538electronic.jpg'
];
$base = public_path('assets/images/categories/');
echo "Base Path: $base\n\n";

foreach ($files as $f) {
    $path = $base . $f;
    echo "Checking $f:\n";
    if (file_exists($path)) {
        echo "  - Exists: YES\n";
        echo "  - Size: " . filesize($path) . " bytes\n";
        echo "  - Permissions: " . substr(sprintf('%o', fileperms($path)), -4) . "\n";
        echo "  - Readable: " . (is_readable($path) ? 'YES' : 'NO') . "\n";
        $type = mime_content_type($path);
        echo "  - Mime: $type\n";
    } else {
        echo "  - Exists: NO\n";
    }
    echo "\n";
}

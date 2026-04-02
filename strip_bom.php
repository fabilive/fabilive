<?php

$directories = [
    'app/Models',
    'app/Policies',
    'app/Http/Controllers',
    'app/Services',
    'database/migrations',
    'app/Filament/Resources',
    'tests/Feature',
    'database/seeders',
];

foreach ($directories as $directory) {
    if (!is_dir($directory)) continue;

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $path = $file->getRealPath();
            $content = file_get_contents($path);
            if (substr($content, 0, 3) === pack('CCC', 0xef, 0xbb, 0xbf)) {
                $newContent = substr($content, 3);
                file_put_contents($path, $newContent);
                echo "Stripped BOM from: $path\n";
            }
        }
    }
}

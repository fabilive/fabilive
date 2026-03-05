<?php
@mkdir(__DIR__ . '/database/old_migrations');
$files = glob(__DIR__ . '/database/migrations/*.php');
foreach ($files as $file) {
    if (strpos($file, '2014_') !== false || strpos($file, '2019_') !== false || strpos($file, '2023_') !== false) {
        $basename = basename($file);
        rename($file, __DIR__ . '/database/old_migrations/' . $basename);
        echo "Moved $basename\n";
    }
}
echo "Done moving files.\n";

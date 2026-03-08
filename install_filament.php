<?php
$output = shell_exec('php composer.phar require filament/filament:"^3.0" --with-all-dependencies --dry-run 2>&1');
file_put_contents('composer_error.txt', $output);
echo "Written to composer_error.txt\n";

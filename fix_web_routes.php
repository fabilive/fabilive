<?php
$file = __DIR__ . '/routes/web.php';
$content = file_get_contents($file);

// Remove null bytes
$content = str_replace("\0", '', $content);

// Remove the corrupted debug.php require line
$content = preg_replace('/require\s+base_path\s*\(\s*[\'"]routes\/debug\.php[\'"]\s*\)\s*;/', '', $content);

// Trim trailing whitespace
$content = rtrim($content) . "\n";

file_put_contents($file, $content);
echo "Fixed web.php - removed null bytes and debug require\n";
echo "File size: " . strlen($content) . " bytes\n";

<?php
// PHP Upload Diagnostic Script
// Upload this to your public/ folder and visit fabilive.com/diag.php

header('Content-Type: text/plain');

echo "--- PHP Upload Configuration ---\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";
echo "max_execution_time: " . ini_get('max_execution_time') . "\n";
echo "max_input_time: " . ini_get('max_input_time') . "\n";

echo "\n--- Server Info ---\n";
echo "Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "\n";
echo "PHP Version: " . phpversion() . "\n";

echo "\n--- Upload Test ---\n";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Files received: " . count($_FILES) . "\n";
    foreach ($_FILES as $key => $file) {
        echo "Field '$key': " . $file['name'] . " (" . round($file['size'] / 1024 / 1024, 2) . " MB) - Error: " . $file['error'] . "\n";
    }
} else {
    echo "No POST data received. Use a tool like Postman or a simple form to test.\n";
}

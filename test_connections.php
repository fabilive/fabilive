<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "Testing MySQL Connection...\n";
try {
    $pdo = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'] . ";dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    echo "MySQL Connected Successfully!\n";
} catch (Exception $e) {
    echo "MySQL Connection Failed: " . $e->getMessage() . "\n";
}

echo "\nTesting Redis Connection...\n";
try {
    $redis = new Redis();
    $connected = $redis->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
    if ($connected) {
        echo "Redis Connected Successfully!\n";
    } else {
        echo "Redis Connection Failed (connect() returned false)\n";
    }
} catch (Exception $e) {
    echo "Redis Connection Failed: " . $e->getMessage() . "\n";
}

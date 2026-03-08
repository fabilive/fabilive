<?php
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $pdo = new PDO("mysql:host=" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'] . ";dbname=" . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    
    echo "--- Users Table ---\n";
    $stmt = $pdo->query("SELECT email, is_vendor FROM users LIMIT 5");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $type = ($row['is_vendor'] == 2) ? 'Seller' : 'Buyer';
        echo "Email: {$row['email']} | Type: {$type}\n";
    }

    echo "\n--- Riders Table ---\n";
    $stmt = $pdo->query("SELECT email FROM riders LIMIT 5");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "Email: {$row['email']}\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

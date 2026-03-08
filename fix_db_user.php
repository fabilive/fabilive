<?php
// Fix database user permissions
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $pdo->exec("CREATE USER IF NOT EXISTS 'fabilive'@'127.0.0.1' IDENTIFIED BY 'fabilive'");
    $pdo->exec("GRANT ALL PRIVILEGES ON fabilive.* TO 'fabilive'@'127.0.0.1'");
    $pdo->exec("CREATE USER IF NOT EXISTS 'fabilive'@'localhost' IDENTIFIED BY 'fabilive'");
    $pdo->exec("GRANT ALL PRIVILEGES ON fabilive.* TO 'fabilive'@'localhost'");
    $pdo->exec("CREATE USER IF NOT EXISTS 'fabilive'@'%' IDENTIFIED BY 'fabilive'");
    $pdo->exec("GRANT ALL PRIVILEGES ON fabilive.* TO 'fabilive'@'%'");
    $pdo->exec("FLUSH PRIVILEGES");
    
    echo "SUCCESS: User created and privileges granted\n";
    
    // Verify
    $stmt = $pdo->query("SELECT user, host FROM mysql.user WHERE user = 'fabilive'");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  User: {$row['user']}@{$row['host']}\n";
    }
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

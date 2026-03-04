<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

DB::statement('DROP TABLE IF EXISTS chats;');
DB::statement('DROP TABLE IF EXISTS wallet_ledger;');
DB::statement('ALTER TABLE users ENGINE=InnoDB;');
DB::statement('ALTER TABLE orders ENGINE=InnoDB;');
echo "Engines updated successfully.\n";

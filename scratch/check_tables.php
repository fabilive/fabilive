<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$tables = ['admin_user_conversations', 'admin_user_messages'];
foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "Table: $table\n";
        print_r(Schema::getColumnListing($table));
        echo "\n";
    } else {
        echo "Table: $table DO NOT EXIST\n";
    }
}

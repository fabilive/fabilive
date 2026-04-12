<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$columns = Illuminate\Support\Facades\Schema::getColumnListing('admin_user_conversations');
print_r($columns);

<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$agreements = DB::table('agreements')->get();
foreach ($agreements as $agreement) {
    echo "ID: {$agreement->id}, Type: {$agreement->type}, Image: {$agreement->image}\n";
}

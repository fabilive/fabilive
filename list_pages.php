<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$pages = DB::table('pages')->get(['title', 'slug', 'header', 'footer']);
foreach ($pages as $page) {
    echo "Title: {$page->title}, Slug: {$page->slug}, Header: {$page->header}, Footer: {$page->footer}\n";
}

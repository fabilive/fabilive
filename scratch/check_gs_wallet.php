<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$cols = DB::getSchemaBuilder()->getColumnListing('generalsettings');
foreach($cols as $col) {
    if(strpos($col, 'wallet') !== false) echo $col . PHP_EOL;
}

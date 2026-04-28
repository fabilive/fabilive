<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

try {
    \Illuminate\Support\Facades\DB::table('categories')->whereIn('name', ['Food', 'Drinks'])->update(['status' => 0]);
    echo "Successfully hid categories.";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}

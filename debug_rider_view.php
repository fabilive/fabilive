<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Find the latest job or specific one
    $job = App\Models\DeliveryJob::with(['order', 'stops.seller', 'events'])->first();
    // Simulate rider
    $rider = App\Models\Rider::find(2);
    if (!$job || !$rider) die("Missing job or rider");

    Auth::guard('web')->loginUsingId($rider->id); // Fabilive riders use web guard

    echo "Rendering view for job " . $job->id . "\n";
    $html = view('rider.delivery.details', compact('job'))->render();
    echo "Success! Length: " . strlen($html) . "\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " on line " . $e->getLine() . "\n";
}

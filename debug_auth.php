<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$job = App\Models\DeliveryJob::find(1); // The one from earlier
echo "Job ID: " . $job->id . "\n";
echo "Assigned Rider ID: " . var_export($job->assigned_rider_id, true) . "\n";
echo "Status: " . var_export($job->status, true) . "\n";

$riderId = 2;
// The check:
if ($job->assigned_rider_id != $riderId && $job->status !== 'available') {
    echo "Check FAILED (Unauthorized)\n";
} else {
    echo "Check PASSED (Authorized)\n";
}

// Any chance 'available' is spelled wrong?

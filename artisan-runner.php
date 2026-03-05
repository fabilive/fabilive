<?php

$output = [];
$exitCode = 0;

// Run multiple artisan commands
exec('php artisan config:clear', $output, $exitCode);
exec('php artisan cache:clear', $output, $exitCode);
exec('php artisan config:cache', $output, $exitCode);

// Display result
echo "<pre>";
print_r($output);
echo "</pre>";

if ($exitCode === 0) {
    echo "✅ Artisan commands ran successfully.";
} else {
    echo "❌ There was an error running Artisan commands.";
}
?>

<?php
// force_admin_reset.php
// This script manually resets the admin password for hello@fabilive.com
// It must be run inside the Docker container context.

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

$email = 'hello@fabilive.com';
$password = 'Fabi@123###';

echo "Attempting to reset password for $email...\n";

try {
    echo "Using updateOrCreate for $email...\n";
    $admin = Admin::updateOrCreate(
        ['email' => $email],
        [
            'password' => Hash::make($password),
            'name'     => 'Super Admin',
            'status'   => 1 // assuming 1 is active
        ]
    );

    if ($admin) {
        echo "SUCCESS: Admin $email is now verified with password $password\n";
    }
} catch (\Exception $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . "\n";
}

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
    $admin = Admin::where('email', $email)->first();
    if ($admin) {
        $admin->password = Hash::make($password);
        $admin->save();
        echo "SUCCESS: Password for $email has been reset to $password\n";
    } else {
        echo "ERROR: Admin with email $email not found in the database.\n";
        
        // Let's check all admins to be sure
        $admins = Admin::all();
        echo "Current admins in database:\n";
        foreach ($admins as $a) {
            echo "- " . $a->email . "\n";
        }
    }
} catch (\Exception $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . "\n";
}

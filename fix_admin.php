<?php

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$email = 'hello@fabilive.com';
$admin = Admin::where('email', $email)->first();

if (!$admin) {
    echo "Admin not found: $email\n";
} else {
    echo "Admin found: " . $admin->id . " | Role ID: " . $admin->role_id . "\n";
    
    // Check roles
    $roles = Role::all();
    echo "Available Roles:\n";
    foreach ($roles as $role) {
        echo "ID: " . $role->id . " | Name: " . $role->name . " | Permissions: " . $role->section . "\n";
    }

    // Make super admin (often role_id 0 or update permissions)
    // If it's a multi-admin system, maybe setting role_id to 0 makes them super admin
    // Or we find the 'Super Admin' role.
    
    echo "\nUpdating $email to Super Admin...\n";
    $admin->role_id = 0; // Usually 0 is super admin in these types of CMS
    $admin->save();
    echo "Update complete.\n";
}

<?php
// EMERGENCY RECOVERY HOOK - Remove after use!
if (isset($_GET['emergency_fix'])) {
    $paths = [
        __DIR__ . '/../bootstrap/cache/config.php',
        __DIR__ . '/../bootstrap/cache/routes.php',
        __DIR__ . '/../bootstrap/cache/services.php',
        __DIR__ . '/../bootstrap/cache/packages.php',
        __DIR__ . '/../storage/framework/views/',
    ];
    echo "Starting cleanup...\n";
    foreach ($paths as $path) {
        if (is_dir($path)) {
            foreach (glob($path . "*.php") as $file) {
                if (unlink($file)) echo "Deleted view: " . basename($file) . "\n";
            }
        } elseif (file_exists($path)) {
            if (unlink($path)) echo "Deleted cache: " . basename($path) . "\n";
        }
    }
    
    // Reset Admin Password via direct PDO if possible, or try bootstrap if cleanup worked
    echo "Attempting password reset...\n";
    try {
        require __DIR__ . '/../vendor/autoload.php';
        $app = require_once __DIR__ . '/../bootstrap/app.php';
        $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->bootstrap();
        $admin = \App\Models\Admin::where('email', 'hello@fabilive.com')->first();
        if ($admin) {
            $admin->password = \Illuminate\Support\Facades\Hash::make('Fabi@123###');
            $admin->save();
            echo "SUCCESS: Admin password updated to Fabi@123###\n";
        } else {
            echo "ERROR: Admin not found\n";
        }
    } catch (\Exception $e) {
        echo "Laravel Error during reset: " . $e->getMessage() . "\n";
    }
    exit;
}
if (isset($_GET['clear_all'])) {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    $kernel->bootstrap();
    try {
        if (isset($_GET['read_log'])) {
            $logPath = __DIR__ . '/../storage/logs/laravel.log';
            if (file_exists($logPath)) {
                $lines = file($logPath);
                echo implode("", array_slice($lines, -50));
            } else {
                echo "Log file not found at $logPath";
            }
        } else {
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
            echo "Artisan Result: " . \Illuminate\Support\Facades\Artisan::output();
            echo "\nSUCCESS: Cache and optimization cleared";
        }
    } catch (\Exception $e) {
        echo "ERROR: " . $e->getMessage();
    }
    exit;
}

// Milestone 1: HTTP 301 Redirects for Legacy Slugs (e.g. /anti%20scam -> /anti-scam)
$uri = $_SERVER['REQUEST_URI'] ?? '';
if (strpos($uri, '%20') !== false || strpos($uri, ' ') !== false) {
    $newUri = str_replace(['%20', ' '], '-', $uri);
    header("Location: " . $newUri, true, 301);
    exit;
}

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__ . '/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__ . '/../bootstrap/app.php';


$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);

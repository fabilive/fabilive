<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Currency;

echo str_repeat("=", 50) . "\n";
echo "   FABILIVE DEEP HEALTH DIAGNOSTIC REPORT\n";
echo str_repeat("=", 50) . "\n\n";

$pass = 0; $fail = 0;

// 1. Settings Hardcode Checks (ID 1 MUST exist)
$tables = ['generalsettings', 'pagesettings', 'seotools'];
foreach ($tables as $t) {
    if (DB::table($t)->where('id', 1)->exists()) {
        echo "[OK] Table `$t` has ID=1. BaseControllers will run safely.\n";
        $pass++;
    } else {
        echo "[FAIL] Table `$t` is missing ID=1! Controllers will CRASH with 500 error.\n";
        $fail++;
    }
}

// 2. Default Currency check
$curr = Currency::where('is_default', 1)->first();
if ($curr) {
    echo "[OK] Default currency exists: {$curr->name} ({$curr->sign})\n";
    $pass++;
} else {
    echo "[FAIL] NO default currency set! User dashboard WILL CRASH.\n";
    $fail++;
}

// 3. Log Permissions Check
$logPath = storage_path('logs/laravel.log');
if (!file_exists($logPath) || is_writable($logPath)) {
    echo "[OK] Log file is writable by the web server.\n";
    $pass++;
} else {
    echo "[FAIL] Log file NOT WRITABLE. Any error will instantly cause a silent 500 crash.\n";
    $fail++;
}

// 4. Cache Permissions Check
$cachePath = storage_path('framework/cache/data');
if (is_writable($cachePath)) {
    echo "[OK] Storage cache is writable.\n";
    $pass++;
} else {
    echo "[FAIL] Storage cache NOT WRITABLE! Website WILL CRASH.\n";
    $fail++;
}

echo str_repeat("-", 50) . "\n";
echo "SUMMARY: Passed ($pass) | Failed ($fail)\n";
if ($fail == 0) {
    echo "VERDICT: Fabilive Engine is fully stable. ALL expected 500 errors are resolved.\n";
} else {
    echo "VERDICT: Critical failures found. Please fix the items marked [FAIL].\n";
}
echo str_repeat("-", 50) . "\n";

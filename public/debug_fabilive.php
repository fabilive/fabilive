<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

echo "<h1>Fabilive Debug Utility</h1>";
echo "Public Path: " . public_path() . "<br>";
echo "Base Path: " . base_path() . "<br>";

$logo_trials = [
    "1739963696logopurplepng.png",
    "1745842520logopurple1png.png",
    "1580538562logo.png",
    "logo.png",
    "fabilive_logo_white_bg.png",
    "fabilive_logo_transparent.png"
];

echo "<h2>Logo Filesystem Check:</h2><ul>";
foreach($logo_trials as $t) {
    $p = public_path('assets/images/'.$t);
    $exists = file_exists($p) ? "<span style='color:green'>FOUND</span>" : "<span style='color:red'>MISSING</span>";
    echo "<li>$t: $exists ($p)</li>";
}
echo "</ul>";

echo "<h2>Slider Filesystem Check:</h2><ul>";
$s_path = public_path('assets/images/sliders/electronics_hero.png');
$s_exists = file_exists($s_path) ? "<span style='color:green'>FOUND</span>" : "<span style='color:red'>MISSING</span>";
echo "<li>electronics_hero.png: $s_exists ($s_path)</li>";
echo "</ul>";

echo "<h2>Database Status:</h2>";
try {
    \DB::connection()->getPdo();
    echo "<span style='color:green'>Connected Successfully</span>";
} catch (\Exception $e) {
    echo "<span style='color:red'>Connection Failed: " . $e->getMessage() . "</span>";
}

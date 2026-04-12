<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

echo "ALL GATEWAYS:\n";
$all = App\Models\PaymentGateway::all();
foreach($all as $g) {
    echo "ID: {$g->id}, Keyword: {$g->keyword}, Checkout: {$g->checkout}, CurrencyID: '{$g->currency_id}', Title: '{$g->title}'\n";
}

$xaf = App\Models\Currency::where('name', 'XAF')->first();
if($xaf) {
    echo "\nGateways for XAF (ID: {$xaf->id}):\n";
    $filtered = App\Models\PaymentGateway::scopeHasGateway($xaf->id);
    foreach($filtered as $g) {
        echo "Keyword: {$g->keyword}\n";
    }
}

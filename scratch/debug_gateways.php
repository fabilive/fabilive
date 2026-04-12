<?php
include 'vendor/autoload.php';
$app = include 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$kernel->handle(Illuminate\Http\Request::capture());

$xaf = App\Models\Currency::where('name', 'XAF')->first();
$curr_id = $xaf ? $xaf->id : 1;
echo "Currency XAF ID: $curr_id\n";

echo "Gateways for XAF (ID: $curr_id):\n";
$gateways = App\Models\PaymentGateway::where('checkout', 1)->get();
foreach ($gateways as $gt) {
    echo "ID: {$gt->id}, Keyword: {$gt->keyword}, CurrencyID: {$gt->currency_id}, Link: " . $gt->showCheckoutLink() . "\n";
}

$xaf_gateways = App\Models\PaymentGateway::scopeHasGateway($curr_id);
echo "\nFiltered Gateways (scopeHasGateway($curr_id)):\n";
foreach ($xaf_gateways as $gt) {
    echo "Keyword: {$gt->keyword}\n";
}

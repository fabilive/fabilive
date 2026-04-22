<?php
// Mocking the environment for verification
function getTieredCommission($price) {
    if ($price <= 5000) {
        return 700;
    } elseif ($price <= 10000) {
        return 800;
    } elseif ($price <= 20000) {
        return 999;
    } elseif ($price <= 30000) {
        return 1000;
    } elseif ($price <= 50000) {
        return 1200;
    } elseif ($price <= 100000) {
        return 1500;
    } else {
        return 2000;
    }
}

$salePrice = 1000;
$regularPrice = 2300;

$finalSale = $salePrice + getTieredCommission($salePrice);
$finalRegular = $regularPrice + getTieredCommission($regularPrice);

echo "Sale Price: 1000 -> Final: " . $finalSale . "\n";
echo "Regular Price: 2300 -> Final: " . $finalRegular . "\n";

if ($finalSale == 1700 && $finalRegular == 3000) {
    echo "SUCCESS: Dual prices are correct.\n";
} else {
    echo "FAILURE: Dual prices are incorrect.\n";
}

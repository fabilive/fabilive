<?php

namespace App\Services;

use App\Models\Generalsetting;

class DeliveryFeeService
{
    /**
     * Calculate delivery fee based on number of unique sellers.
     * Formula: BaseFee + StopoverFee * (SellersCount - 1)
     */
    public function calculateFee(int $sellersCount): array
    {
        $gs = Generalsetting::first();
        $baseFee = $gs->delivery_base_fee ?? 1000;
        $stopoverFee = $gs->delivery_stopover_fee ?? 300;

        $total = $baseFee;
        if ($sellersCount > 1) {
            $total += ($sellersCount - 1) * $stopoverFee;
        }

        // Calculate platform commission and rider earnings
        // Default: Platform takes 20% of the delivery fee, Rider gets 80%
        $riderPercent = $gs->rider_percentage_commission ?? 80;
        $riderEarnings = ($total * $riderPercent) / 100;
        $platformCommission = $total - $riderEarnings;

        return [
            'base_fee' => $baseFee,
            'stopover_fee' => $stopoverFee,
            'total' => $total,
            'platform_commission' => $platformCommission,
            'rider_earnings' => $riderEarnings,
        ];
    }
}

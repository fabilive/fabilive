<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AntiScamService
{
    /**
     * Analyze a listing or user for scam signals.
     * Returns array of detected signals with risk scores.
     */
    public function analyze(array $context): array
    {
        $signals = [];

        if (isset($context['phone'])) {
            $phoneSignals = $this->checkPhone($context['phone']);
            $signals = array_merge($signals, $phoneSignals);
        }

        if (isset($context['price'], $context['category'])) {
            $priceSignals = $this->checkPricing($context['price'], $context['category']);
            $signals = array_merge($signals, $priceSignals);
        }

        if (isset($context['description'])) {
            $textSignals = $this->checkText($context['description']);
            $signals = array_merge($signals, $textSignals);
        }

        // Calculate overall risk score
        $totalRisk = array_sum(array_column($signals, 'risk_score'));
        $maxRisk = min($totalRisk, 100);

        return [
            'risk_score' => $maxRisk,
            'signals' => $signals,
            'recommendation' => $maxRisk >= 70 ? 'block' : ($maxRisk >= 40 ? 'review' : 'allow'),
        ];
    }

    /**
     * Check phone number for suspicious patterns.
     */
    protected function checkPhone(string $phone): array
    {
        $signals = [];
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        // Too short
        if (strlen($cleaned) < 8) {
            $signals[] = [
                'signal_type' => 'suspicious_phone',
                'reason_code' => 'phone_too_short',
                'details' => "Phone number too short: {$phone}",
                'risk_score' => 30,
            ];
        }

        // Repeated digits (e.g., 000000000)
        if (preg_match('/(\d)\1{5,}/', $cleaned)) {
            $signals[] = [
                'signal_type' => 'suspicious_phone',
                'reason_code' => 'phone_repeated_digits',
                'details' => "Phone has repeated digits: {$phone}",
                'risk_score' => 40,
            ];
        }

        // Sequential digits (e.g., 123456789)
        if (preg_match('/123456789|987654321/', $cleaned)) {
            $signals[] = [
                'signal_type' => 'suspicious_phone',
                'reason_code' => 'phone_sequential',
                'details' => "Phone has sequential digits: {$phone}",
                'risk_score' => 35,
            ];
        }

        return $signals;
    }

    /**
     * Check pricing for outliers.
     */
    protected function checkPricing(float $price, string $category): array
    {
        $signals = [];

        // Price is zero or negative
        if ($price <= 0) {
            $signals[] = [
                'signal_type' => 'pricing_outlier',
                'reason_code' => 'zero_price',
                'details' => "Price is $price for category: $category",
                'risk_score' => 50,
            ];
            return $signals;
        }

        // Check against category median (if we have enough data)
        $stats = DB::table('products')
            ->where('status', 1)
            ->where('price', '>', 0)
            ->selectRaw('AVG(price) as avg_price, STDDEV(price) as stddev_price, COUNT(*) as total')
            ->first();

        if ($stats && $stats->total >= 10 && $stats->stddev_price > 0) {
            $zScore = abs(($price - $stats->avg_price) / $stats->stddev_price);

            if ($zScore > 3) {
                $signals[] = [
                    'signal_type' => 'pricing_outlier',
                    'reason_code' => 'extreme_price',
                    'details' => "Price {$price} is {$zScore}σ from mean ({$stats->avg_price})",
                    'risk_score' => min(60, $zScore * 15),
                ];
            }
        }

        return $signals;
    }

    /**
     * Check text for off-platform payment keywords.
     */
    protected function checkText(string $text): array
    {
        $signals = [];
        $lower = strtolower($text);

        $suspiciousPatterns = [
            'send money directly' => 'off_platform_payment',
            'western union' => 'off_platform_payment',
            'moneygram' => 'off_platform_payment',
            'pay outside' => 'off_platform_payment',
            'whatsapp me' => 'off_platform_contact',
            'call me direct' => 'off_platform_contact',
            'advance payment' => 'advance_payment_scam',
            'pay before delivery' => 'advance_payment_scam',
            'gift card' => 'gift_card_scam',
        ];

        foreach ($suspiciousPatterns as $pattern => $type) {
            if (str_contains($lower, $pattern)) {
                $signals[] = [
                    'signal_type' => 'suspicious_text',
                    'reason_code' => $type,
                    'details' => "Text contains suspicious pattern: \"{$pattern}\"",
                    'risk_score' => 25,
                ];
            }
        }

        return $signals;
    }

    /**
     * Store detected signals in the database.
     */
    public function recordSignals(array $signals, int $userId = null, int $listingId = null): void
    {
        foreach ($signals as $signal) {
            DB::table('scam_signals')->insert([
                'flagged_user_id' => $userId,
                'flagged_listing_id' => $listingId,
                'signal_type' => $signal['signal_type'],
                'reason_code' => $signal['reason_code'],
                'details' => $signal['details'] ?? null,
                'risk_score' => $signal['risk_score'],
                'review_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

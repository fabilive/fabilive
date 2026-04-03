<?php

namespace App\Services\AI;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReputationService
{
    /**
     * Badge definitions: type => computation criteria.
     */
    const BADGES = [
        'fast_responder' => [
            'label' => 'Fast Responder',
            'description' => 'Responds to delivery chats quickly',
        ],
        'honest_pricing' => [
            'label' => 'Honest Pricing',
            'description' => 'Fair and consistent pricing',
        ],
        'trusted' => [
            'label' => 'Trusted Seller',
            'description' => 'High completion rate, low complaints',
        ],
    ];

    /**
     * Compute reputation badges for a seller.
     */
    public function computeBadges(User $seller): array
    {
        $badges = [];

        // Fast Responder: average chat response time < 5 minutes
        $responseScore = $this->computeResponseScore($seller);
        if ($responseScore >= 80) {
            $badges[] = [
                'badge_type' => 'fast_responder',
                'score' => $responseScore,
                'active' => true,
            ];
        }

        // Honest Pricing: no pricing outlier signals
        $pricingScore = $this->computePricingScore($seller);
        if ($pricingScore >= 70) {
            $badges[] = [
                'badge_type' => 'honest_pricing',
                'score' => $pricingScore,
                'active' => true,
            ];
        }

        // Trusted: high completion rate, low complaints
        $trustScore = $this->computeTrustScore($seller);
        if ($trustScore >= 75) {
            $badges[] = [
                'badge_type' => 'trusted',
                'score' => $trustScore,
                'active' => true,
            ];
        }

        // Save badges
        foreach ($badges as $badge) {
            DB::table('seller_badges')->updateOrInsert(
                ['user_id' => $seller->id, 'badge_type' => $badge['badge_type']],
                [
                    'score' => $badge['score'],
                    'active' => $badge['active'],
                    'earned_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        return $badges;
    }

    /**
     * Response score: based on delivery chat response times.
     */
    protected function computeResponseScore(User $seller): float
    {
        // Check completed delivery jobs where seller participated
        $completedJobs = DB::table('delivery_jobs')
            ->where('seller_id', $seller->id)
            ->where('status', 'delivered')
            ->count();

        if ($completedJobs === 0) {
            return 0;
        }

        // More completions = higher base score
        $base = min(100, $completedJobs * 10);

        // Penalize for cancellations
        $cancelledJobs = DB::table('delivery_jobs')
            ->where('seller_id', $seller->id)
            ->where('status', 'cancelled')
            ->count();

        $cancellationPenalty = $cancelledJobs * 15;

        return max(0, $base - $cancellationPenalty);
    }

    /**
     * Pricing score: inverse of scam pricing signals.
     */
    protected function computePricingScore(User $seller): float
    {
        $pricingFlags = DB::table('scam_signals')
            ->where('flagged_user_id', $seller->id)
            ->where('signal_type', 'pricing_outlier')
            ->count();

        if ($pricingFlags === 0) {
            return 100;
        }

        return max(0, 100 - ($pricingFlags * 20));
    }

    /**
     * Trust score: completion rate minus complaints.
     */
    protected function computeTrustScore(User $seller): float
    {
        $totalOrders = DB::table('orders')
            ->where('user_id', $seller->id)
            ->count();

        if ($totalOrders === 0) {
            return 50; // Neutral for new sellers
        }

        $complaints = DB::table('complaints')
            ->where('user_id', $seller->id)
            ->count();

        $complaintRate = $complaints / max($totalOrders, 1);

        // Base 100, penalize for complaints
        return max(0, min(100, 100 - ($complaintRate * 200)));
    }

    /**
     * Get active badges for a seller (for display).
     */
    public function getActiveBadges(int $userId): array
    {
        return DB::table('seller_badges')
            ->where('user_id', $userId)
            ->where('active', true)
            ->get()
            ->map(function ($badge) {
                $info = self::BADGES[$badge->badge_type] ?? [];

                return [
                    'type' => $badge->badge_type,
                    'label' => $info['label'] ?? $badge->badge_type,
                    'description' => $info['description'] ?? '',
                    'score' => $badge->score,
                    'earned_at' => $badge->earned_at,
                ];
            })
            ->toArray();
    }
}

<?php

namespace App\Services;

use App\Models\Generalsetting;
use App\Models\ReferralCode;
use App\Models\ReferralUsage;
use App\Models\Rider;
use App\Models\User;
use App\Models\WalletLedger;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReferralService
{
    /**
     * Generate a unique referral code for a user or rider.
     */
    public function generateCode($owner, string $role = 'buyer'): ReferralCode
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $owner->name ?? 'FAB'), 0, 4));
        $code = $prefix.strtoupper(Str::random(6));

        // Ensure uniqueness
        while (ReferralCode::where('code', $code)->exists()) {
            $code = $prefix.strtoupper(Str::random(6));
        }

        if ($role === 'vendor') {
            $role = 'seller';
        }

        $data = [
            'code' => $code,
            'owner_role' => $role,
        ];

        if ($owner instanceof Rider) {
            $data['rider_id'] = $owner->id;
        } else {
            $data['user_id'] = $owner->id;
        }

        return ReferralCode::create($data);
    }

    /**
     * Apply a referral code during registration.
     * Awards bonus to BOTH referrer and referred user via WalletLedger.
     *
     * @throws Exception
     */
    public function applyReferral(string $code, $newUser, string $role = 'buyer'): ?ReferralUsage
    {
        // Sign-up rewards are disabled as per new referral program requirements.
        // Referral codes are now used as coupons during checkout.
        return null;
    }

    /**
     * Validate a referral code for use as a coupon.
     * 
     * @throws Exception
     */
    public function validateReferralForCoupon(string $code, $user): ReferralCode
    {
        $gs = \App\Models\Generalsetting::safeFirst();

        if (!$gs || !($gs->referral_system_active ?? true)) {
            throw new Exception('Referral system is currently disabled.');
        }

        // Normalize code input
        $code = strtoupper(trim($code));
        $referralCode = ReferralCode::where('code', $code)->first();

        if (!$referralCode) {
            throw new Exception('Invalid referral code.');
        }

        if (!$referralCode->isActive()) {
            throw new Exception('This referral code is no longer active.');
        }

        if ($user) {
            // Anti-abuse: no self-referral
            if ($referralCode->user_id === $user->id) {
                throw new Exception('You cannot use your own referral code.');
            }

            // Global constraint: Only one referral code usage allowed in a lifetime
            $hasUsedReferral = ReferralUsage::where('referred_user_id', $user->id)
                ->where('status', 'awarded')
                ->exists();
            if ($hasUsedReferral) {
                throw new Exception('You have already used a referral code before.');
            }

            // First purchase check
            $orderCount = \App\Models\Order::where('user_id', $user->id)->count();
            if ($orderCount > 0) {
                throw new Exception('Referral discounts are only available for your first purchase.');
            }

            // 30-day registration limit check
            $daysSinceRegistration = $user->created_at->diffInDays(now());
            if ($daysSinceRegistration > 30) {
                throw new Exception('Referral discounts are only valid for the first 30 days of registration.');
            }
        }

        return $referralCode;
    }

    /**
     * Award referral rewards after a successful purchase.
     */
    public function applyReferralReward($order)
    {
        if (empty($order->coupon_code)) return;

        $referralCode = ReferralCode::where('code', $order->coupon_code)->first();
        if (!$referralCode) return;

        // Rewards are fixed in this version
        $referrerReward = 100;

        DB::transaction(function () use ($referralCode, $order, $referrerReward) {
            // Create usage record
            ReferralUsage::create([
                'referral_code_id' => $referralCode->id,
                'referred_user_id' => $order->user_id,
                'referrer_bonus' => $referrerReward,
                'referred_bonus' => 0, // Buyer got 200 off immediate discount
                'status' => 'awarded',
            ]);

            // Award referrer to LOCKED balance
            $referrer = User::lockForUpdate()->find($referralCode->user_id);
            if ($referrer) {
                $referrer->referral_locked_balance = ($referrer->referral_locked_balance ?? 0) + $referrerReward;
                
                // Threshold Check: 25,000 XFA
                if ($referrer->referral_locked_balance >= 25000) {
                    $amountToUnlock = $referrer->referral_locked_balance;
                    $referrer->current_balance += $amountToUnlock;
                    $referrer->referral_locked_balance = 0;

                    WalletLedger::create([
                        'user_id' => $referrer->id,
                        'amount' => $amountToUnlock,
                        'type' => 'referral_bonus',
                        'status' => 'completed',
                        'reference' => 'UNLOCK-REF-' . $referralCode->code,
                        'details' => "Unlocked referral rewards (Threshold 25,000 reached)",
                    ]);
                }
                
                $referrer->save();
            }

            $referralCode->increment('usages_count');
        });
    }

    /**
     * Get referral stats for a user.
     */
    public function getStats($owner): array
    {
        $referralCode = null;

        if ($owner instanceof Rider) {
            $referralCode = ReferralCode::where('rider_id', $owner->id)->first();
        } else {
            $referralCode = ReferralCode::where('user_id', $owner->id)->first();
        }

        if (! $referralCode) {
            return [
                'code' => null,
                'total_referrals' => 0,
                'total_earned' => 0,
                'remaining_uses' => 0,
            ];
        }

        $totalEarned = $referralCode->usages()
            ->where('status', 'awarded')
            ->sum('referrer_bonus');

        return [
            'code' => $referralCode->code,
            'total_referrals' => $referralCode->usages_count,
            'total_earned' => $totalEarned,
            'remaining_uses' => $referralCode->max_usages - $referralCode->usages_count,
        ];
    }

    /**
     * Hash a value for anti-abuse duplicate detection.
     */
    private function hashValue(string $value): ?string
    {
        $cleaned = trim(strtolower($value));

        return $cleaned ? hash('sha256', $cleaned) : null;
    }
}

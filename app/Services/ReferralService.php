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
    public function applyReferral(string $code, $newUser, string $role = 'buyer'): ReferralUsage
    {
        $gs = Generalsetting::first();

        if (! $gs || ! ($gs->referral_system_active ?? true)) {
            throw new Exception('Referral system is currently disabled.');
        }

        $referralCode = ReferralCode::where('code', $code)->first();

        if (! $referralCode) {
            throw new Exception('Invalid referral code.');
        }

        if (! $referralCode->isActive()) {
            throw new Exception('This referral code is no longer active.');
        }

        // Anti-abuse: no self-referral
        if ($newUser instanceof User && $referralCode->user_id === $newUser->id) {
            throw new Exception('You cannot use your own referral code.');
        }
        if ($newUser instanceof Rider && $referralCode->rider_id === $newUser->id) {
            throw new Exception('You cannot use your own referral code.');
        }

        // Anti-abuse: check duplicate phone/email
        $phoneHash = $this->hashValue($newUser->phone ?? '');
        $emailHash = $this->hashValue($newUser->email ?? '');

        if ($phoneHash && ReferralUsage::where('phone_hash', $phoneHash)->where('status', 'awarded')->exists()) {
            throw new Exception('A referral bonus has already been claimed with this phone number.');
        }

        if ($emailHash && ReferralUsage::where('email_hash', $emailHash)->where('status', 'awarded')->exists()) {
            throw new Exception('A referral bonus has already been claimed with this email.');
        }

        $referrerBonus = $gs->referral_bonus_referrer ?? 500;
        $referredBonus = $gs->referral_bonus_referred ?? 250;

        return DB::transaction(function () use ($referralCode, $newUser, $role, $referrerBonus, $referredBonus, $phoneHash, $emailHash) {

            // Create usage record
            $usageData = [
                'referral_code_id' => $referralCode->id,
                'referred_role' => $role,
                'referrer_bonus' => $referrerBonus,
                'referred_bonus' => $referredBonus,
                'status' => 'awarded',
                'phone_hash' => $phoneHash,
                'email_hash' => $emailHash,
            ];

            if ($newUser instanceof Rider) {
                $usageData['referred_rider_id'] = $newUser->id;
            } else {
                $usageData['referred_user_id'] = $newUser->id;
            }

            $usage = ReferralUsage::create($usageData);

            // Award referrer via WalletLedger
            $referrerId = $referralCode->user_id;
            if ($referrerId) {
                $referrer = User::find($referrerId);
                if ($referrer) {
                    $referrer->current_balance = ($referrer->current_balance ?? 0) + $referrerBonus;
                    $referrer->save();

                    WalletLedger::create([
                        'user_id' => $referrer->id,
                        'amount' => $referrerBonus,
                        'type' => 'referral_bonus',
                        'status' => 'completed',
                        'reference' => 'REF-'.$referralCode->code,
                        'details' => "Referral bonus for inviting user via code {$referralCode->code}",
                    ]);
                }
            }

            // Award referred user
            if ($newUser instanceof User) {
                $newUser->current_balance = ($newUser->current_balance ?? 0) + $referredBonus;
                $newUser->save();

                WalletLedger::create([
                    'user_id' => $newUser->id,
                    'amount' => $referredBonus,
                    'type' => 'referral_bonus',
                    'status' => 'completed',
                    'reference' => 'RREF-'.$referralCode->code,
                    'details' => "Welcome bonus from referral code {$referralCode->code}",
                ]);
            }

            // Increment usage count
            $referralCode->increment('usages_count');

            return $usage;
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

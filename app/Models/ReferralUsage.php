<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralUsage extends Model
{
    protected $fillable = [
        'referral_code_id', 'referred_user_id', 'referred_rider_id',
        'referred_role', 'referrer_bonus', 'referred_bonus',
        'status', 'phone_hash', 'email_hash',
    ];

    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class);
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    public function referredRider()
    {
        return $this->belongsTo(Rider::class, 'referred_rider_id');
    }
}

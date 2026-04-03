<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralCode extends Model
{
    protected $fillable = [
        'user_id', 'rider_id', 'code', 'owner_role',
        'usages_count', 'max_usages', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $attributes = [
        'active' => true,
        'usages_count' => 0,
        'max_usages' => 100,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function usages()
    {
        return $this->hasMany(ReferralUsage::class);
    }

    public function isActive(): bool
    {
        return $this->active && $this->usages_count < $this->max_usages;
    }
}

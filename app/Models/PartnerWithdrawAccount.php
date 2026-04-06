<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartnerWithdrawAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'user_type', 
        'method', 
        'acc_name', 
        'acc_number', 
        'bank_name', 
        'iban', 
        'swift', 
        'network', 
        'address', 
        'is_default'
    ];

    /**
     * Get the owning user model (Vendor or Rider).
     */
    public function user()
    {
        return $this->morphTo();
    }
}

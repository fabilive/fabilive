<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayoutRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role',
        'amount',
        'method',
        'destination',
        'status',
        'admin_action_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

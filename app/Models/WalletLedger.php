<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletLedger extends Model
{
    use HasFactory;

    protected $table = 'wallet_ledger';

    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'type',
        'reference',
        'status',
        'details',
    ];
}

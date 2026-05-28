<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSaleTimeSlot extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_time', 'end_time', 'status'];

    public function products()
    {
        return $this->hasMany(FlashSaleProduct::class, 'time_slot_id');
    }
}

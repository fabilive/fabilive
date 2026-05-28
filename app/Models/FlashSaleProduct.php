<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashSaleProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'vendor_id', 'time_slot_id', 'flash_date', 
        'flash_price', 'flash_quantity', 'sold_quantity', 'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withDefault();
    }

    public function timeSlot()
    {
        return $this->belongsTo(FlashSaleTimeSlot::class, 'time_slot_id')->withDefault();
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id')->withDefault();
    }
}

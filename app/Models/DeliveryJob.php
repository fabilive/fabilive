<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'buyer_id',
        'status',
        'service_area_id',
        'base_fee',
        'stopover_fee',
        'sellers_count',
        'delivery_fee_total',
        'platform_delivery_commission',
        'rider_earnings',
        'assigned_rider_id',
        'proof_photo',
        'proof_uploaded_at',
        'accepted_at',
        'picked_up_at',
        'delivered_at',
        'cancelled_at',
        'returned_at',
    ];

    protected $casts = [
        'proof_uploaded_at' => 'datetime',
        'accepted_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class, 'assigned_rider_id');
    }

    public function stops()
    {
        return $this->hasMany(DeliveryJobStop::class)->orderBy('sequence');
    }

    public function events()
    {
        return $this->hasMany(DeliveryJobEvent::class);
    }

    public function serviceArea()
    {
        return $this->belongsTo(ServiceArea::class);
    }

    public function chatThreads()
    {
        return $this->hasMany(DeliveryChatThread::class);
    }
}

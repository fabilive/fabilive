<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryRider extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'vendor_id',
        'rider_id',
        'service_area_id',
        'pickup_point_id',
        'phone_number',
        'status',
        'more_info',
    ];

    public function serviceAreas()
    {
        return $this->hasOneThrough(
            ServiceArea::class,         // Final model
            RiderServiceArea::class,    // Intermediate model
            'id',                       // FK on RiderServiceArea (its PK)
            'id',                       // FK on ServiceArea (its PK)
            'service_area_id',           // Local key on DeliveryRider
            'service_area_id'            // Local key on RiderServiceArea
        );
    }

    public function rider()
    {
        return $this->belongsTo(Rider::class);
    }

    public function servicesarea()
    {
        return $this->belongsTo(ServiceArea::class, 'service_area_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function pick()
    {
        return $this->belongsTo(Pickup::class, 'pickup_point_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class);
    }

    public function pickup()
    {
        return $this->belongsTo(\App\Models\Pickup::class, 'pickup_point_id');
    }

    // Service area relationship
    public function serviceArea()
    {
        return $this->belongsTo(\App\Models\ServiceArea::class, 'service_area_id');
    }
}

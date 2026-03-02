<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class DeliveryRider extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [];
    public function rider()
    {
        return $this->belongsTo(Rider::class);
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
        return $this->belongsTo(PickupPoint::class, 'pickup_point_id')->withDefault();
    }
    public function servicearea()
    {
        return $this->belongsTo(RiderServiceArea::class, 'service_area_id')->withDefault();
    }
}

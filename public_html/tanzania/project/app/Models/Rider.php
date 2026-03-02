<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Rider extends Authenticatable
{
    protected $fillable = ['name', 'photo', 'zip', 'city_id', 'state_id', 'country', 'address', 'phone', 'fax', 'email', 'password','vehicle_type', 'location', 'email_verify', 'email_verified', 'email_token', 'status', 'balance', 'national_id_front_image', 'national_id_back_image', 'license_image','submerchant_agreement'];
    protected $hidden = [
        'password', 'remember_token'
    ];
    public function orders()
    {
        return $this->hasMany('App\Models\DeliveryRider');
    }
    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
    public function state()
    {
        return $this->belongsTo('App\Models\State');
    }
    public function serviceArea()
{
    return $this->belongsTo(ServiceArea::class, 'service_area_id');
}
}

<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Rider extends Authenticatable implements JWTSubject
{
    protected $fillable = ['name', 'photo', 'zip', 'city_id', 'state_id', 'country', 'address', 'phone', 'fax', 'email', 'password', 'location', 'email_verify', 'email_verified', 'email_token', 'status', 'is_available', 'balance', 'national_id_front_image', 'national_id_back_image', 'license_image', 'submerchant_agreement',
    'rider_type','company_registration_document','id_company_owner','live_selfie_company',
    'transport_license','insurance_certificate_company','tin_company','rider_status',
    'vehicle_type_individual','tin_individual','driver_license_individual',
    'live_selfie_individual','vehicle_registration_certificate',
    'insurance_certificate_individual','criminal_records', 'is_verified',
    'onboarding_status', 'rejection_reason', 'approved_at'];
    protected $hidden   = [
        'password', 'remember_token'
    ];
    public function orders()
    {
        return $this->hasMany('App\Models\DeliveryJob', 'assigned_rider_id');
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
    public function serviceAreas()
    {
        return $this->belongsToMany(ServiceArea::class,'rider_service_areas');
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function agreements()
{
    return $this->hasMany(ManageAgreement::class, 'rider_id');
}

}

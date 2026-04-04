<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function service_areas()
    {
        return $this->hasMany(ServiceArea::class, 'city_id');
    }

    protected $fillable = ['city_name', 'name', 'state_id', 'country_id', 'status', 'latitude', 'longitude'];

    protected $appends = ['city_name', 'name'];

    public function getCityNameAttribute()
    {
        return $this->attributes['city_name'] ?? $this->attributes['name'] ?? null;
    }

    public function getNameAttribute()
    {
        return $this->attributes['name'] ?? $this->attributes['city_name'] ?? null;
    }

    public $timestamps = false;
}

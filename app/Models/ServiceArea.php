<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceArea extends Model
{
    protected $table = 'service_areas';

    protected $fillable = ['location', 'latitude', 'longitude', 'name', 'base_fee', 'stopover_fee', 'status', 'city_id'];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public $timestamps = false;
}

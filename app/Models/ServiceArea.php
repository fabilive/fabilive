<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceArea extends Model
{
    protected $table = 'service_areas';

    protected $fillable = ['location', 'latitude', 'longitude', 'name', 'base_fee', 'stopover_fee', 'status'];

    public $timestamps = false;
}

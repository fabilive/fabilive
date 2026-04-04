<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'country_name', 'status', 'phone_code'];

    protected $appends = ['name', 'country_name'];

    public function getCountryNameAttribute()
    {
        return $this->attributes['country_name'] ?? $this->attributes['name'] ?? null;
    }

    public function getNameAttribute()
    {
        return $this->attributes['name'] ?? $this->attributes['country_name'] ?? null;
    }

    public function states()
    {
        return $this->hasMany('App\Models\State');
    }
}

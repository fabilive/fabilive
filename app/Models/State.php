<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public $timestamps = false;

    protected $fillable = ['state', 'name', 'country_id', 'status', 'tax'];

    protected $appends = ['state', 'name'];

    public function getStateAttribute()
    {
        return $this->attributes['state'] ?? $this->attributes['name'] ?? null;
    }

    public function getNameAttribute()
    {
        return $this->attributes['name'] ?? $this->attributes['state'] ?? null;
    }

    public function country()
    {
        return $this->belongsTo('App\Models\Country')->withDefault();
    }

    public function cities()
    {
        return $this->hasMany('App\Models\City');
    }
}

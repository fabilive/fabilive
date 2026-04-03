<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = ['user_id', 'title', 'subtitle', 'price'];

    public $timestamps = false;

    public function orders()
    {
        return $this->belongsTo(Order::class);
    }
}

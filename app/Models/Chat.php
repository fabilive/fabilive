<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table='delivery_chat_threads';
    protected $guarded=[];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
    public function messages()
{
    return $this->hasMany(ChatMessages::class, 'chat_id');
}

public function rider()
{
    return $this->belongsTo(\App\Models\Rider::class, 'rider_id', 'id');
}

// App\Models\Chat.php
public function order()
{
    return $this->belongsTo(Order::class, 'order_id');
}


}

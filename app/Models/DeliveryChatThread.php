<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryChatThread extends Model
{
    protected $fillable = [
        'delivery_job_id',
        'thread_type',
        'seller_id',
        'buyer_id',
        'rider_id',
        'hidden_at'
    ];

    protected $casts = [
        'hidden_at' => 'datetime'
    ];

    public function deliveryJob()
    {
        return $this->belongsTo(DeliveryJob::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'delivery_chat_thread_id');
    }
}

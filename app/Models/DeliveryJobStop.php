<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryJobStop extends Model
{
    protected $fillable = [
        'delivery_job_id',
        'type',
        'seller_id',
        'sequence',
        'status',
        'location_text',
        'lat',
        'lng',
        'ready_at',
        'arrived_at',
        'picked_up_at'
    ];

    protected $casts = [
        'ready_at' => 'datetime',
        'arrived_at' => 'datetime',
        'picked_up_at' => 'datetime',
    ];

    public function deliveryJob()
    {
        return $this->belongsTo(DeliveryJob::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryJobEvent extends Model
{
    protected $fillable = [
        'delivery_job_id',
        'actor_type',
        'actor_id',
        'event',
        'meta_json'
    ];

    protected $casts = [
        'meta_json' => 'array'
    ];

    public function deliveryJob()
    {
        return $this->belongsTo(DeliveryJob::class);
    }

    public function actor()
    {
        // Polymorphic or simple relation based on actor_type
        if ($this->actor_type === 'admin') {
            return $this->belongsTo(Admin::class, 'actor_id');
        }
        return $this->belongsTo(User::class, 'actor_id');
    }
}

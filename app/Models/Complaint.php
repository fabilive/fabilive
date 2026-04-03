<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'user_id', 'order_id', 'subject', 'description',
        'status', 'priority', 'admin_response',
        'assigned_admin_id', 'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'open',
        'priority' => 'medium',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(Admin::class, 'assigned_admin_id');
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['open', 'in_progress']);
    }

    public function resolve(string $response, int $adminId): void
    {
        $this->admin_response = $response;
        $this->assigned_admin_id = $adminId;
        $this->status = 'resolved';
        $this->resolved_at = now();
        $this->save();
    }
}

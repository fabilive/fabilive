<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationPreference extends Model
{
    protected $fillable = [
        'user_id', 'notification_type',
        'email_enabled', 'push_enabled', 'in_app_enabled',
        'quiet_hours_start', 'quiet_hours_end'
    ];

    protected $casts = [
        'email_enabled' => 'boolean',
        'push_enabled' => 'boolean',
        'in_app_enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this notification type is in quiet hours right now.
     */
    public function isInQuietHours(): bool
    {
        if (!$this->quiet_hours_start || !$this->quiet_hours_end) {
            return false;
        }

        $now = now()->format('H:i');
        $start = $this->quiet_hours_start;
        $end = $this->quiet_hours_end;

        if ($start <= $end) {
            return $now >= $start && $now <= $end;
        }

        // Overnight quiet hours (e.g. 22:00–06:00)
        return $now >= $start || $now <= $end;
    }
}

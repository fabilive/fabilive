<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmartNotificationService
{
    /** Max notifications per type per day */
    const FREQUENCY_CAP = 5;

    /**
     * Check if a notification should be sent based on preferences, quiet hours, and frequency caps.
     */
    public function shouldSend(int $userId, string $type, string $channel = 'in_app'): bool
    {
        // Check frequency cap (applies always, regardless of preferences)
        $recentCount = DB::table('notification_logs')
            ->where('user_id', $userId)
            ->where('notification_type', $type)
            ->where('sent_at', '>=', now()->subDay())
            ->count();

        if ($recentCount >= self::FREQUENCY_CAP) {
            return false;
        }

        $pref = NotificationPreference::where('user_id', $userId)
            ->where('notification_type', $type)
            ->first();

        // If no preference set, default to sending
        if (! $pref) {
            return true;
        }

        // Check channel enabled
        $channelField = $channel.'_enabled';
        if (isset($pref->{$channelField}) && ! $pref->{$channelField}) {
            return false;
        }

        // Check quiet hours
        if ($pref->isInQuietHours()) {
            return false;
        }

        return true;
    }

    /**
     * Send a notification, check preferences first.
     */
    public function send(int $userId, string $type, array $data = [], string $channel = 'in_app'): bool
    {
        if (! $this->shouldSend($userId, $type, $channel)) {
            return false;
        }

        // Create in-app notification
        if ($channel === 'in_app') {
            Notification::create(array_merge([
                'user_id' => $userId,
            ], $data));
        }

        // Log the notification
        DB::table('notification_logs')->insert([
            'user_id' => $userId,
            'notification_type' => $type,
            'channel' => $channel,
            'sent_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return true;
    }

    /**
     * Set a user's notification preferences.
     */
    public function setPreference(int $userId, string $type, array $settings): NotificationPreference
    {
        return NotificationPreference::updateOrCreate(
            ['user_id' => $userId, 'notification_type' => $type],
            $settings
        );
    }
}

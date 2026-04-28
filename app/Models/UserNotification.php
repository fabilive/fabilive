<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class UserNotification extends Model
{
    protected $fillable = [
        'user_id',
        'recipient_type',
        'recipient_id',
        'order_number',
        'type',
        'message',
        'url',
        'icon',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // ── Relationships ─────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_number', 'order_number')->withDefault();
    }

    // ── Scopes ────────────────────────────────────────────────

    /**
     * Scope to unread notifications only.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', 0);
    }

    /**
     * Scope for a specific recipient (unified: user, rider, admin).
     */
    public function scopeForRecipient($query, string $type, int $id)
    {
        return $query->where('recipient_type', $type)
                     ->where('recipient_id', $id);
    }

    /**
     * Scope for legacy user_id-based queries (backward compatible).
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('user_id', $userId)
              ->orWhere(function ($q2) use ($userId) {
                  $q2->where('recipient_type', 'user')
                     ->where('recipient_id', $userId);
              });
        });
    }

    // ── Static Helpers ────────────────────────────────────────

    /**
     * Count unread notifications for a given user (auto-detects guard: buyer or rider).
     */
    public static function countOrder($id = null)
    {
        try {
            if (!\App\Models\Generalsetting::isDbValid() || !Schema::hasTable('user_notifications')) {
                return 0;
            }

            // If ID is null, try to get from current auth context
            if (!$id) {
                if (auth()->guard('rider')->check()) {
                    return static::forRecipient('rider', auth()->guard('rider')->id())->unread()->count();
                }
                if (auth()->guard('web')->check()) {
                    return static::forUser(auth()->guard('web')->id())->unread()->count();
                }
                return 0;
            }

            // If ID is provided, we need to know the type. 
            // For backward compatibility, we assume 'user' (Buyer/Seller) if not specified elsewhere.
            // But let's try to detect if that ID belongs to a rider first if we are in a rider context.
            if (auth()->guard('rider')->check() && auth()->guard('rider')->id() == $id) {
                return static::forRecipient('rider', $id)->unread()->count();
            }

            return UserNotification::forUser($id)->unread()->count();
        } catch (\Exception $e) {
            return 0;
        }
    }


    /**
     * Count unread notifications for any recipient type.
     */
    public static function countForRecipient(string $type, int $id): int
    {
        try {
            if (\App\Models\Generalsetting::isDbValid() && Schema::hasTable('user_notifications')) {
                return static::forRecipient($type, $id)->unread()->count();
            }
        } catch (\Exception $e) {
            return 0;
        }

        return 0;
    }
}

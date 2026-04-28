<?php

namespace App\Http\Controllers\User;

use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends UserBaseController
{
    /**
     * Get unread notification count for the authenticated user/rider.
     */
    public function count()
    {
        if (auth()->guard('rider')->check()) {
            $count = UserNotification::forRecipient('rider', auth()->guard('rider')->id())->unread()->count();
        } else {
            $count = UserNotification::forUser(Auth::id())->unread()->count();
        }

        return response()->json($count);
    }

    /**
     * Show all notifications for the authenticated user/rider (marks them as read).
     */
    public function show()
    {
        if (auth()->guard('rider')->check()) {
            $datas = UserNotification::forRecipient('rider', auth()->guard('rider')->id())
                ->latest()
                ->take(20)
                ->get();
        } else {
            $datas = UserNotification::forUser(Auth::id())
                ->latest()
                ->take(20)
                ->get();
        }

        // Mark all as read
        if ($datas->count() > 0) {
            foreach ($datas as $data) {
                if (!$data->is_read) {
                    $data->is_read = 1;
                    $data->update();
                }
            }
        }

        return view('user.notification.index', compact('datas'));
    }

    /**
     * Clear all notifications for the authenticated user/rider.
     */
    public function clear()
    {
        if (auth()->guard('rider')->check()) {
            UserNotification::forRecipient('rider', auth()->guard('rider')->id())->delete();
        } else {
            UserNotification::forUser(Auth::id())->delete();
        }

        return response()->json(['success' => true]);
    }
}


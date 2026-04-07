<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class UserNotification extends Model
{
    public static function countOrder($id)
    {
        try {
            if (\App\Models\Generalsetting::isDbValid() && Schema::hasTable('user_notifications')) {
                return UserNotification::where('user_id', '=', $id)->where('is_read', '=', 0)->count();
            }
        } catch (\Exception $e) {
            return 0;
        }

        return 0;
    }
}

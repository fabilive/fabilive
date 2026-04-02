<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportAgent extends Model
{
    protected $fillable = ["admin_id", "is_online", "max_active_chats", "last_seen_at"];
    protected $casts = ["is_online" => "boolean", "last_seen_at" => "datetime"];

    public function admin() { return $this->belongsTo(Admin::class); }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected $fillable = ["conversation_id", "sender_type", "sender_id", "type", "body_text", "attachment_url", "attachment_mime", "attachment_size", "voice_duration"];

    public function conversation() { return $this->belongsTo(SupportConversation::class, "conversation_id"); }
}

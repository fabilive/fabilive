<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportConversation extends Model
{
    protected $fillable = ['requester_user_id', 'context', 'status', 'assigned_agent_admin_id', 'started_at', 'assigned_at', 'ended_at', 'ended_by', 'rating_required'];

    protected $casts = ['started_at' => 'datetime', 'assigned_at' => 'datetime', 'ended_at' => 'datetime', 'rating_required' => 'boolean'];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_user_id');
    }

    public function assignedAgent()
    {
        return $this->belongsTo(Admin::class, 'assigned_agent_admin_id');
    }

    public function messages()
    {
        return $this->hasMany(SupportMessage::class, 'conversation_id');
    }

    public function rating()
    {
        return $this->hasOne(SupportRating::class, 'conversation_id');
    }

    public function events()
    {
        return $this->hasMany(SupportConversationEvent::class, 'conversation_id');
    }
}

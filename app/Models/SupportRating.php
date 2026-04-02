<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportRating extends Model
{
    protected $fillable = ["conversation_id", "agent_admin_id", "rater_user_id", "rating", "comment"];

    public function conversation() { return $this->belongsTo(SupportConversation::class, "conversation_id"); }
    public function agent() { return $this->belongsTo(Admin::class, "agent_admin_id"); }
    public function rater() { return $this->belongsTo(User::class, "rater_user_id"); }
}

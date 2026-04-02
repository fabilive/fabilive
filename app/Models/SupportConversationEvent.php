<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportConversationEvent extends Model
{
    public $timestamps = false;
    protected $fillable = ["conversation_id", "actor_type", "actor_id", "event", "meta_json", "created_at"];
    protected $casts = ["meta_json" => "array", "created_at" => "datetime"];

    public function conversation() { return $this->belongsTo(SupportConversation::class, "conversation_id"); }
}

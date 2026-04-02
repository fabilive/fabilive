<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportBotRule extends Model
{
    protected $fillable = ["context", "pattern_type", "pattern_value", "response_text", "suggested_faq_id", "priority", "is_active"];
    protected $casts = ["is_active" => "boolean"];

    public function suggestedFaq() { return $this->belongsTo(SupportFaq::class, "suggested_faq_id"); }
}

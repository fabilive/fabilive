<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportFaq extends Model
{
    protected $fillable = ["category_id", "context", "question", "answer_html", "keywords", "is_active", "sort_order"];
    protected $casts = ["keywords" => "array", "is_active" => "boolean"];

    public function category() { return $this->belongsTo(SupportFaqCategory::class, "category_id"); }
}

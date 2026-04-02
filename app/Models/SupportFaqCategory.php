<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportFaqCategory extends Model
{
    protected $fillable = ["context", "name", "sort_order", "is_active"];
    protected $casts = ["is_active" => "boolean"];

    public function faqs() { return $this->hasMany(SupportFaq::class, "category_id"); }
}

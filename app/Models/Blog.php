<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['title', 'slug', 'category_id', 'details', 'photo', 'source', 'views', 'updated_at', 'status', 'meta_tag', 'meta_description', 'tags'];

    protected $dates = ['created_at'];

    public $timestamps = true;


    public function category()
    {
        return $this->belongsTo('App\Models\BlogCategory', 'category_id')->withDefault();
    }
}

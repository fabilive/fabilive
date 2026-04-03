<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'subtitle' => $this->subtitle_text,
            'title' => $this->title_text,
            'small_text' => $this->details_text,
            'image' => url('/').'/assets/images/sliders/'.$this->photo,
            'redirect_url' => $this->link,
            'category_id' => optional($this->product)->category_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

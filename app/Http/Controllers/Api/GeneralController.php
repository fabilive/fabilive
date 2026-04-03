<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ServiceAreaResource;
use App\Models\ServiceArea;

class GeneralController
{
    public function serviceAreas()
    {
        $service_area = ServiceArea::orderBy('id', 'desc')->get();

        return response()->json(['status' => true, 'data' => ServiceAreaResource::collection($service_area)]);
    }

}

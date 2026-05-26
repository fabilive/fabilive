<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image as Image;

class GalleryController extends Controller
{
    public function show()
    {
        $data[0] = 0;
        $id = $_GET['id'];
        $prod = Product::findOrFail($id);
        if (count($prod->galleries)) {
            $data[0] = 1;
            $data[1] = $prod->galleries;
        }

        return response()->json($data);
    }

    public function store(Request $request)
    {
        $data = null;
        $lastid = $request->product_id;
        if ($files = $request->file('gallery')) {
            foreach ($files as $key => $file) {
                $val = strtolower($file->getClientOriginalExtension());
                if (in_array($val, ['jpeg', 'jpg', 'png', 'svg', 'webp', 'gif', 'jfif'])) {
                    $gallery = new Gallery;

                    $thumbnail = \App\Helpers\ImageHelper::processImage($file->getRealPath(), public_path('assets/images/galleries'), 800, 800, true, $val);

                    $gallery['photo'] = $thumbnail;
                    $gallery['product_id'] = $lastid;
                    $gallery->save();
                    $data[] = $gallery;
                }
            }
        }

        return response()->json($data);
    }

    public function destroy()
    {

        $id = $_GET['id'];
        $gal = Gallery::findOrFail($id);
        if (file_exists('assets/images/galleries/'.$gal->photo)) {
            unlink('assets/images/galleries/'.$gal->photo);
        }
        $gal->delete();

    }
}

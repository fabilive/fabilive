<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\Generalsetting;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;
use App\Helpers\PriceHelper;
use Intervention\Image\Facades\Image as Image;
use Yajra\DataTables\Facades\DataTables;

class ImportController extends VendorBaseController
{
    //*** JSON Request
    public function datatables()
    {
        try {
            $user = $this->user;
            $datas = $user->products()->where('product_type', 'affiliate')->latest('id')->get();
        } catch (\Exception $e) {
            $datas = collect();
        }

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('name', function (Product $data) {
                $name = mb_strlen(strip_tags($data->name), 'UTF-8') > 50 ? mb_substr(strip_tags($data->name), 0, 50, 'UTF-8').'...' : strip_tags($data->name);
                $id = '<small>'.__('Product ID').': <a href="'.route('front.product', $data->slug).'" target="_blank">'.sprintf("%'.08d", $data->id).'</a></small>';

                return $name.'<br>'.$id;
            })
            ->editColumn('price', function (Product $data) {
                $curr = $this->curr ?? \App\Models\Currency::where('is_default', 1)->first() ?? \App\Models\Currency::where('id', '>', 0)->first();
                $val = $curr ? $curr->value : 1;
                $price = round($data->price * $val, 2);

                return \PriceHelper::showAdminCurrencyPrice($price);
            })
            ->addColumn('status', function (Product $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';

                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'.route('vendor-prod-status', ['id1' => $data->id, 'id2' => 1]).'" '.$s.'>'.__('Activated').'</option><<option data-val="0" value="'.route('vendor-prod-status', ['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>'.__('Deactivated').'</option></select></div>';
            })
            ->addColumn('action', function (Product $data) {
                return '<div class="action-list"><a href="'.route('vendor-import-edit', $data->id).'"> <i class="fas fa-edit"></i>'.__('Edit').'</a><a href="javascript" class="set-gallery" data-toggle="modal" data-target="#setgallery"><input type="hidden" value="'.$data->id.'"><i class="fas fa-eye"></i> '.__('View Gallery').'</a><a href="javascript:;" data-href="'.route('vendor-prod-delete', $data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->rawColumns(['name', 'status', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index()
    {
        try {
            if ($this->gs && isset($this->gs->affilite) && $this->gs->affilite == 1) {
                return view('vendor.productimport.index');
            }
        } catch (\Exception $e) {
        }

        return back();
    }

    //*** GET Request
    public function createImport()
    {
        $cats = collect();
        try {
            $cats = Category::all();
        } catch (\Exception $e) {}

        $sign = $this->curr;
        if ($this->gs && isset($this->gs->affilite) && $this->gs->affilite == 1) {
            return view('vendor.productimport.createone', compact('cats', 'sign'));
        } else {
            return back();
        }
    }

    //*** GET Request
    public function importCSV()
    {
        try {
            $cats = Category::all();
        } catch (\Exception $e) {
            $cats = collect();
        }
        $sign = $this->curr;

        return view('vendor.productimport.importcsv', compact('cats', 'sign'));
    }

    //*** POST Request
    public function uploadUpdate(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            'image' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $data = Product::findOrFail($id);

        //--- Validation Section Ends
        $image = $request->image;
        [$type, $image] = explode(';', $image);
        [, $image] = explode(',', $image);
        $image = base64_decode($image);
        $image_name = time().Str::random(8).'.png';
        $path = 'assets/images/products/'.$image_name;
        file_put_contents($path, $image);
        if ($data->photo != null) {
            if (file_exists(public_path().'/assets/images/products/'.$data->photo)) {
                unlink(public_path().'/assets/images/products/'.$data->photo);
            }
        }
        $input['photo'] = $image_name;
        $data->update($input);

        return response()->json(['status' => true, 'file_name' => $image_name]);
    }

    //*** POST Request
    // public function store(Request $request)
    // {
    //     $user = $this->user;
    //     $package = $user->subscribes()->latest('id')->first();
    //     $prods = $user->products()->latest('id')->get()->count();

    //     if(Generalsetting::find(1)->verify_product == 1)
    //     {
    //         if(!$user->checkStatus())
    //         {
    //             return response()->json(array('errors' => [ 0 => __('You must complete your verfication first.')]));
    //         }
    //     }

    //     if($prods < $package->allowed_products)
    //     {

    //     if($request->image_source == 'file')
    //     {
    //         //--- Validation Section
    //         $rules = [
    //               'photo'      => 'required',
    //               'file'       => 'mimes:zip'
    //                 ];

    //     $validator = Validator::make($request->all(), $rules);

    //     if ($validator->fails()) {
    //       return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
    //     }
    //     //--- Validation Section Ends

    //     }

    //     //--- Logic Section
    //         $data = new Product;
    //         $sign = $this->curr;
    //         $input = $request->all();

    //         // Check File
    //         if ($file = $request->file('file'))
    //         {
    //             $name = \PriceHelper::ImageCreateName($file);
    //             $file->move('assets/files',$name);
    //             $input['file'] = $name;
    //         }

    //         $input['photo'] = "";
    //         if($request->photo != ""){
    //             $image = $request->photo;
    //             list($type, $image) = explode(';', $image);
    //             list(, $image)      = explode(',', $image);
    //             $image = base64_decode($image);
    //             $image_name = time().Str::random(8).'.png';
    //             $path = 'assets/images/products/'.$image_name;
    //             file_put_contents($path, $image);
    //             $input['photo'] = $image_name;
    //         }else{
    //             $input['photo'] = $request->photolink;
    //         }

    //         // Check Physical
    //         if($request->type == "Physical")
    //         {
    //                 //--- Validation Section
    //                 $rules = ['sku'      => 'min:8|unique:products'];

    //                 $validator = Validator::make($request->all(), $rules);

    //                 if ($validator->fails()) {
    //                     return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
    //                 }
    //                 //--- Validation Section Ends

    //         // Check Condition
    //         if ($request->product_condition_check == ""){
    //             $input['product_condition'] = 0;
    //         }

    //                     // Check Preorderd
    //                     if ($request->preordered_check == ""){
    //                         $input['preordered'] = 0;
    //                     }

    //         // Check Shipping Time
    //         if ($request->shipping_time_check == ""){
    //             $input['ship'] = null;
    //         }

    //         // Check Size
    //         if(empty($request->stock_check ))
    //         {
    //             $input['stock_check'] = 0;
    //             $input['size'] = null;
    //             $input['size_qty'] = null;
    //             $input['size_price'] = null;
    //             $input['color'] = null;
    //         }
    //         else{
    //                 if(in_array(null, $request->size) || in_array(null, $request->size_qty) || in_array(null, $request->size_price))
    //                 {
    //                     $input['stock_check'] = 0;
    //                     $input['size'] = null;
    //                     $input['size_qty'] = null;
    //                     $input['size_price'] = null;
    //                     $input['color'] = null;
    //                 }
    //                 else
    //                 {
    //                     $input['stock_check'] = 1;
    //                     $input['color'] = implode(',', $request->color);
    //                     $input['size'] = implode(',', $request->size);
    //                     $input['size_qty'] = implode(',', $request->size_qty);
    //                     $size_prices = $request->size_price;
    //                     $s_price = array();
    //                     foreach($size_prices as $key => $sPrice){
    //                         $s_price[$key] = $sPrice / $sign->value;
    //                     }

    //                     $input['size_price'] = implode(',', $s_price);
    //                 }
    //         }

    //         // Check Color
    //         if(empty($request->color_check))
    //         {
    //             $input['color_all'] = null;
    //         }
    //         else{
    //             $input['color_all'] = implode(',', $request->color_all);
    //         }
    //         // Check Size
    //         if(empty($request->size_check))
    //         {
    //             $input['size_all'] = null;
    //         }
    //         else{
    //             $input['size_all'] = implode(',', $request->size_all);
    //         }

    //         // Check Measurement
    //         if ($request->mesasure_check == "")
    //          {
    //             $input['measure'] = null;
    //          }

    //         }

    //         // Check Seo
    //     if (empty($request->seo_check))
    //      {
    //         $input['meta_tag'] = null;
    //         $input['meta_description'] = null;
    //      }
    //      else {
    //     if (!empty($request->meta_tag))
    //      {
    //         $input['meta_tag'] = implode(',', $request->meta_tag);
    //      }
    //      }

    //          // Check License

    //         if($request->type == "License")
    //         {

    //             if(in_array(null, $request->license) || in_array(null, $request->license_qty))
    //             {
    //                 $input['license'] = null;
    //                 $input['license_qty'] = null;
    //             }
    //             else
    //             {
    //                 $input['license'] = implode(',,', $request->license);
    //                 $input['license_qty'] = implode(',', $request->license_qty);
    //             }

    //         }

    //          // Check Features
    //         if(in_array(null, $request->features) || in_array(null, $request->colors))
    //         {
    //             $input['features'] = null;
    //             $input['colors'] = null;
    //         }
    //         else
    //         {
    //             $input['features'] = implode(',', str_replace(',',' ',$request->features));
    //             $input['colors'] = implode(',', str_replace(',',' ',$request->colors));
    //         }

    //         //tags
    //         if (!empty($request->tags))
    //          {
    //             $input['tags'] = implode(',', $request->tags);
    //          }

    //         // Conert Price According to Currency
    //          $input['price'] = ($input['price'] / $sign->value);
    //          $input['previous_price'] = ($input['previous_price'] / $sign->value);
    //          $input['product_type'] = "affiliate";
    //          $input['user_id'] = $this->user->id;
    //         // Save Data
    //             $data->fill($input)->save();

    //         // Set SLug
    //             $prod = Product::find($data->id);
    //             if($prod->type != 'Physical'){
    //                 $prod->slug = Str::slug($data->name,'-').'-'.strtolower(Str::random(3).$data->id.Str::random(3));
    //             }
    //             else {
    //                 $prod->slug = Str::slug($data->name,'-').'-'.strtolower($data->sku);
    //             }

    //             $fimageData = public_path().'/assets/images/products/'.$prod->photo;

    //             if(filter_var($prod->photo,FILTER_VALIDATE_URL)){
    //                 $fimageData = $prod->photo;
    //             }

    //             $img = Image::make($fimageData)->resize(285, 285);
    //             $thumbnail = time().Str::random(8).'.jpg';
    //             $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
    //             $prod->thumbnail  = $thumbnail;
    //             $prod->update();

    //         // Add To Gallery If any
    //             $lastid = $data->id;
    //             if ($files = $request->file('gallery')){
    //                 foreach ($files as  $key => $file){
    //                     if(in_array($key, $request->galval))
    //                     {
    //                         $gallery = new Gallery;
    //                         $name = \PriceHelper::ImageCreateName($file);
    //                         $file->move('assets/images/galleries',$name);
    //                         $gallery['photo'] = $name;
    //                         $gallery['product_id'] = $lastid;
    //                         $gallery->save();
    //                     }
    //                 }
    //             }
    //     //logic Section Ends

    //     //--- Redirect Section
    //     $msg = __('New Affiliate Product Added Successfully.').'<a href="'.route('vendor-import-index').'">'.__('View Product Lists.').'</a>';
    //     return response()->json($msg);
    //     //--- Redirect Section Ends
    //     }
    //     else
    //     {
    //     //--- Redirect Section
    //     return response()->json(array('errors' => [ 0 => __('You Can\'t Add More Product.')]));

    //     //--- Redirect Section Ends
    //     }

    // }

    // public function store(Request $request)
    // {
    //     try {
    //         $request->all();
    //         $user = $this->user;
    //         $package = $user->subscribes()->latest('id')->first();
    //         $prods = $user->products()->latest('id')->get()->count();

    //         if (Generalsetting::find(1)->verify_product == 1) {
    //             if (!$user->checkStatus()) {
    //                 return response()->json(['errors' => [0 => __('You must complete your verification first.')]]);
    //             }
    //         }

    //         if ($prods >= $package->allowed_products) {
    //             return response()->json(['errors' => [0 => __('You Can\'t Add More Product.')]]);
    //         }

    //         if ($request->image_source == 'file') {
    //             $rules = [
    //                 'photo' => 'required',
    //                 'file'  => 'mimes:zip'
    //             ];
    //             $validator = Validator::make($request->all(), $rules);
    //             if ($validator->fails()) {
    //                 return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
    //             }
    //         }

    //         $data = new Product;
    //         $sign = $this->curr;
    //         $input = $request->all();

    //         if ($file = $request->file('file')) {
    //             $name = \PriceHelper::ImageCreateName($file);
    //             $file->move('assets/files', $name);
    //             $input['file'] = $name;
    //         }

    //         $input['photo'] = "";

    //         if ($request->photo != "") {
    //             $image = $request->photo;
    //             list($type, $image) = explode(';', $image);
    //             list(, $image) = explode(',', $image);
    //             $image = base64_decode($image);
    //             $image_name = time() . Str::random(8) . '.png';
    //             $path = 'assets/images/products/' . $image_name;
    //             file_put_contents($path, $image);
    //             $input['photo'] = $image_name;
    //         } else {
    //             $input['photo'] = $request->photolink;
    //         }

    //         if ($request->type == "Physical") {
    //             $rules = ['sku' => 'min:8|unique:products'];
    //             $validator = Validator::make($request->all(), $rules);
    //             if ($validator->fails()) {
    //                 return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
    //             }

    //             $input['product_condition'] = $request->product_condition_check ? $request->product_condition : 0;
    //             $input['preordered'] = $request->preordered_check ? $request->preordered : 0;
    //             $input['ship'] = $request->shipping_time_check ? $request->ship : null;

    //             if (empty($request->stock_check)) {
    //                 $input['stock_check'] = 0;
    //                 $input['size'] = null;
    //                 $input['size_qty'] = null;
    //                 $input['size_price'] = null;
    //                 $input['color'] = null;
    //             } else {
    //                 if (in_array(null, $request->size) || in_array(null, $request->size_qty) || in_array(null, $request->size_price)) {
    //                     $input['stock_check'] = 0;
    //                     $input['size'] = null;
    //                     $input['size_qty'] = null;
    //                     $input['size_price'] = null;
    //                     $input['color'] = null;
    //                 } else {
    //                     $input['stock_check'] = 1;
    //                     $input['color'] = implode(',', $request->color);
    //                     $input['size'] = implode(',', $request->size);
    //                     $input['size_qty'] = implode(',', $request->size_qty);

    //                     $s_price = [];
    //                     foreach ($request->size_price as $key => $sPrice) {
    //                         $s_price[$key] = $sPrice / $sign->value;
    //                     }

    //                     $input['size_price'] = implode(',', $s_price);
    //                 }
    //             }

    //             $input['color_all'] = empty($request->color_check) ? null : implode(',', $request->color_all);
    //             $input['size_all'] = empty($request->size_check) ? null : implode(',', $request->size_all);
    //             $input['measure'] = $request->mesasure_check ? $request->measure : null;
    //         }

    //         if (empty($request->seo_check)) {
    //             $input['meta_tag'] = null;
    //             $input['meta_description'] = null;
    //         } else {
    //             $input['meta_tag'] = !empty($request->meta_tag) ? implode(',', $request->meta_tag) : null;
    //         }

    //         if ($request->type == "License") {
    //             $input['license'] = in_array(null, $request->license) ? null : implode(',,', $request->license);
    //             $input['license_qty'] = in_array(null, $request->license_qty) ? null : implode(',', $request->license_qty);
    //         }

    //         if ($request->has('features') && $request->has('colors')) {
    //             if (in_array(null, $request->features) || in_array(null, $request->colors)) {
    //                 $input['features'] = null;
    //                 $input['colors'] = null;
    //             } else {
    //                 $input['features'] = implode(',', str_replace(',', ' ', $request->features));
    //                 $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
    //             }
    //         } else {
    //             $input['features'] = null;
    //             $input['colors'] = null;
    //         }

    //         $input['tags'] = !empty($request->tags) ? implode(',', $request->tags) : null;
    //         $input['price'] = $input['price'] / $sign->value;
    //         $input['previous_price'] = $input['previous_price'] / $sign->value;
    //         $input['product_type'] = "affiliate";
    //         $input['user_id'] = $this->user->id;
    //         $input['product_location'] = $request->product_location;

    //         $data->fill($input)->save();

    //         $prod = Product::find($data->id);

    //         $prod->slug = $prod->type != 'Physical'
    //             ? Str::slug($data->name, '-') . '-' . strtolower(Str::random(3) . $data->id . Str::random(3))
    //             : Str::slug($data->name, '-') . '-' . strtolower($data->sku);

    //         $fimageData = public_path() . '/assets/images/products/' . $prod->photo;

    //         if (filter_var($prod->photo, FILTER_VALIDATE_URL)) {
    //             $fimageData = $prod->photo;
    //         }

    //         $img = Image::make($fimageData)->resize(285, 285);
    //         $thumbnail = time() . Str::random(8) . '.jpg';
    //         $img->save(public_path() . '/assets/images/thumbnails/' . $thumbnail);
    //         $prod->thumbnail = $thumbnail;
    //         $prod->update();

    //         if ($files = $request->file('gallery')) {
    //             foreach ($files as $key => $file) {
    //                 if (in_array($key, $request->galval)) {
    //                     $gallery = new Gallery;
    //                     $name = \PriceHelper::ImageCreateName($file);
    //                     $file->move('assets/images/galleries', $name);
    //                     $gallery['photo'] = $name;
    //                     $gallery['product_id'] = $data->id;
    //                     $gallery->save();
    //                 }
    //             }
    //         }

    //         $msg = __('New Affiliate Product Added Successfully.') . '<a href="' . route('vendor-import-index') . '">' . __('View Product Lists.') . '</a>';
    //         return response()->json($msg);
    //     } catch (\Exception $e) {
    //         return response()->json(['errors' => [0 => $e->getMessage()]]);
    //     }
    // }

    public function store(Request $request)
    {
        try {
            $request->all();
            $user = $this->user;
            $package = $user->subscribes()->latest('id')->first();
            $prods = $user->products()->latest('id')->get()->count();

            if (($this->gs->verify_product ?? 0) == 1) {
                if (! $user->checkStatus()) {
                    return response()->json(['errors' => [0 => __('You must complete your verification first.')]]);
                }
            }

            if ($prods >= $package->allowed_products) {
                return response()->json(['errors' => [0 => __('You Can\'t Add More Product.')]]);
            }

            if ($request->image_source == 'file') {
                $rules = [
                    'photo' => 'required',
                    'file' => 'mimes:zip',
                ];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
                }
            }

            $data = new Product;
            $sign = $this->curr;
            $input = $request->all();

            if ($file = $request->file('file')) {
                $name = \PriceHelper::ImageCreateName($file);
                $file->move('assets/files', $name);
                $input['file'] = $name;
            }

            $input['photo'] = '';

            if ($request->photo != '') {
                $image = $request->photo;
                [$type, $image] = explode(';', $image);
                [, $image] = explode(',', $image);
                $image = base64_decode($image);
                $image_name = time().Str::random(8).'.png';
                $path = 'assets/images/products/'.$image_name;
                file_put_contents($path, $image);
                $input['photo'] = $image_name;
            } else {
                $input['photo'] = $request->photolink;
            }

            if ($request->type == 'Physical') {
                $rules = ['sku' => 'min:8|unique:products'];
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
                }

                $input['product_condition'] = $request->product_condition_check ? $request->product_condition : 0;
                $input['preordered'] = $request->preordered_check ? $request->preordered : 0;
                $input['ship'] = $request->shipping_time_check ? $request->ship : null;

                if (empty($request->stock_check)) {
                    $input['stock_check'] = 0;
                    $input['size'] = null;
                    $input['size_qty'] = null;
                    $input['size_price'] = null;
                    $input['color'] = null;
                } else {
                    if (in_array(null, $request->size) || in_array(null, $request->size_qty) || in_array(null, $request->size_price)) {
                        $input['stock_check'] = 0;
                        $input['size'] = null;
                        $input['size_qty'] = null;
                        $input['size_price'] = null;
                        $input['color'] = null;
                    } else {
                        $input['stock_check'] = 1;
                        $input['color'] = is_array($request->color) ? implode(',', $request->color) : null;
                        $input['size'] = is_array($request->size) ? implode(',', $request->size) : null;
                        $input['size_qty'] = is_array($request->size_qty) ? implode(',', $request->size_qty) : null;

                        $s_price = [];
                        foreach ($request->size_price as $key => $sPrice) {
                            $s_price[$key] = $sPrice / $sign->value;
                        }

                        $input['size_price'] = is_array($s_price) ? implode(',', $s_price) : null;
                    }
                }

                $input['color_all'] = empty($request->color_check) ? null : implode(',', $request->color_all);
                $input['size_all'] = empty($request->size_check) ? null : implode(',', $request->size_all);
                $input['measure'] = $request->mesasure_check ? $request->measure : null;
            }

            if (empty($request->seo_check)) {
                $input['meta_tag'] = null;
                $input['meta_description'] = null;
            } else {
                $input['meta_tag'] = ! empty($request->meta_tag) ? implode(',', $request->meta_tag) : null;
            }

            if ($request->type == 'License') {
                $input['license'] = in_array(null, $request->license) ? null : implode(',,', $request->license);
                $input['license_qty'] = in_array(null, $request->license_qty) ? null : implode(',', $request->license_qty);
            }

            if ($request->has('features') && $request->has('colors')) {
                if (in_array(null, $request->features) || in_array(null, $request->colors)) {
                    $input['features'] = null;
                    $input['colors'] = null;
                } else {
                    $input['features'] = is_array($request->features) ? implode(',', str_replace(',', ' ', $request->features)) : null;
                    $input['colors'] = is_array($request->colors) ? implode(',', str_replace(',', ' ', $request->colors)) : null;
                }
            } else {
                $input['features'] = null;
                $input['colors'] = null;
            }

            $input['tags'] = is_array($request->tags) ? implode(',', $request->tags) : null;
            $input['price'] = $input['price'] / $sign->value;
            $input['previous_price'] = $input['previous_price'] / $sign->value;
            $input['product_type'] = 'affiliate';
            $input['user_id'] = $this->user->id;
            $input['product_location'] = $request->product_location;

            $data->fill($input)->save();

            $prod = Product::find($data->id);

            $prod->slug = $prod->type != 'Physical'
                ? Str::slug($data->name, '-').'-'.strtolower(Str::random(3).$data->id.Str::random(3))
                : Str::slug($data->name, '-').'-'.strtolower($data->sku);

            $fimageData = public_path().'/assets/images/products/'.$prod->photo;

            if (filter_var($prod->photo, FILTER_VALIDATE_URL)) {
                $fimageData = $prod->photo;
            }

            $img = Image::make($fimageData)->resize(285, 285);
            $thumbnail = time().Str::random(8).'.jpg';
            $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
            $prod->thumbnail = $thumbnail;
            $prod->update();

            if ($files = $request->file('gallery')) {
                foreach ($files as $key => $file) {
                    if (in_array($key, $request->galval)) {
                        $gallery = new Gallery;
                        $name = \PriceHelper::ImageCreateName($file);
                        $file->move('assets/images/galleries', $name);
                        $gallery['photo'] = $name;
                        $gallery['product_id'] = $data->id;
                        $gallery->save();
                    }
                }
            }

            $msg = __('New Affiliate Product Added Successfully.').'<a href="'.route('vendor-import-index').'">'.__('View Product Lists.').'</a>';

            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['errors' => [0 => $e->getMessage()]]);
        }
    }

    //*** GET Request
    public function edit($id)
    {
        $cats = collect();
        try {
            $cats = Category::all();
        } catch (\Exception $e) {}

        try {
            $data = Product::findOrFail($id);
        } catch (\Exception $e) {
            return back()->with('error', __('Data not found.'));
        }

        $sign = $this->curr;

        return view('vendor.productimport.editone', compact('cats', 'data', 'sign'));
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        try {
            $prod = Product::find($id);
            if (!$prod) {
                return response()->json(['errors' => [0 => __('Product not found.')]]);
            }
            //--- Validation Section
            $rules = [
                'file' => 'mimes:zip',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
            }
            //--- Validation Section Ends
        } catch (\Exception $e) {
            return response()->json(['errors' => [0 => $e->getMessage()]]);
        }

        //-- Logic Section
        $data = Product::findOrFail($id);
        $sign = $this->curr;
        $input = $request->all();

        //Check Types
        if ($request->type_check == 1) {
            $input['link'] = null;
        } else {
            if ($data->file != null) {
                if (file_exists(public_path().'/assets/files/'.$data->file)) {
                    unlink(public_path().'/assets/files/'.$data->file);
                }
            }
            $input['file'] = null;
        }

        if ($request->image_source == 'file') {
            $input['photo'] = $request->photo;
        } else {
            $input['photo'] = $request->photolink;
        }

        // Check Physical
        if ($data->type == 'Physical') {
            //--- Validation Section
            $rules = ['sku' => 'min:8|unique:products,sku,'.$id];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
            }
            //--- Validation Section Ends

            // Check Condition
            if ($request->product_condition_check == '') {
                $input['product_condition'] = 0;
            }

            // Check Preorderd
            if ($request->preordered_check == '') {
                $input['preordered'] = 0;
            }

            // Check Shipping Time
            if ($request->shipping_time_check == '') {
                $input['ship'] = null;
            }

            // Check Size

            if (empty($request->stock_check)) {
                $input['stock_check'] = 0;
                $input['size'] = null;
                $input['size_qty'] = null;
                $input['size_price'] = null;
                $input['color'] = null;
            } else {
                if (in_array(null, $request->size) || in_array(null, $request->size_qty) || in_array(null, $request->size_price)) {
                    $input['stock_check'] = 0;
                    $input['size'] = null;
                    $input['size_qty'] = null;
                    $input['size_price'] = null;
                    $input['color'] = null;
                } else {
                    $input['stock_check'] = 1;
                    $input['color'] = implode(',', $request->color);
                    $input['size'] = implode(',', $request->size);
                    $input['size_qty'] = implode(',', $request->size_qty);
                    $size_prices = $request->size_price;
                    $s_price = [];
                    foreach ($size_prices as $key => $sPrice) {
                        $s_price[$key] = $sPrice / $sign->value;
                    }

                    $input['size_price'] = implode(',', $s_price);
                }
            }

            // Check Color
            if (empty($request->color_check)) {
                $input['color_all'] = null;
            } else {
                $input['color_all'] = implode(',', $request->color_all);
            }
            // Check Size
            if (empty($request->size_check)) {
                $input['size_all'] = null;
            } else {
                $input['size_all'] = implode(',', $request->size_all);
            }

            // Check Measure
            if ($request->measure_check == '') {
                $input['measure'] = null;
            }
        }

        // Check Seo
        if (empty($request->seo_check)) {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;
        } else {
            if (! empty($request->meta_tag)) {
                $input['meta_tag'] = implode(',', $request->meta_tag);
            }
        }

        // Check License
        if ($data->type == 'License') {

            if (! in_array(null, $request->license) && ! in_array(null, $request->license_qty)) {
                $input['license'] = implode(',,', $request->license);
                $input['license_qty'] = implode(',', $request->license_qty);
            } else {
                if (in_array(null, $request->license) || in_array(null, $request->license_qty)) {
                    $input['license'] = null;
                    $input['license_qty'] = null;
                } else {
                    $license = explode(',,', $prod->license);
                    $license_qty = explode(',', $prod->license_qty);
                    $input['license'] = implode(',,', $license);
                    $input['license_qty'] = implode(',', $license_qty);
                }
            }

        }
        // Check Features
        if (! in_array(null, $request->features) && ! in_array(null, $request->colors)) {
            $input['features'] = implode(',', str_replace(',', ' ', $request->features));
            $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
        } else {
            if (in_array(null, $request->features) || in_array(null, $request->colors)) {
                $input['features'] = null;
                $input['colors'] = null;
            } else {
                $features = explode(',', $data->features);
                $colors = explode(',', $data->colors);
                $input['features'] = implode(',', $features);
                $input['colors'] = implode(',', $colors);
            }
        }

        //Product Tags
        if (! empty($request->tags)) {
            $input['tags'] = implode(',', $request->tags);
        }
        if (empty($request->tags)) {
            $input['tags'] = null;
        }

        $input['price'] = $input['price'] / $sign->value;
        $input['previous_price'] = $input['previous_price'] / $sign->value;

        $data->slug = Str::slug($data->name, '-').'-'.strtolower($data->sku);
        $data->update($input);

        //-- Logic Section Ends

        if ($data->photo != null) {
            if (file_exists(public_path().'/assets/images/thumbnails/'.$data->thumbnail)) {
                unlink(public_path().'/assets/images/thumbnails/'.$data->thumbnail);
            }
        }

        $fimageData = public_path().'/assets/images/products/'.$prod->photo;

        if (filter_var($prod->photo, FILTER_VALIDATE_URL)) {
            $fimageData = $prod->photo;
        }

        $img = Image::make($fimageData)->resize(285, 285);
        $thumbnail = time().Str::random(8).'.jpg';
        $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
        $prod->thumbnail = $thumbnail;
        $prod->update();

        //--- Redirect Section
        $msg = __('Product Updated Successfully.').'<a href="'.route('vendor-import-index').'">'.__('View Product Lists.').'</a>';

        return response()->json($msg);
        //--- Redirect Section Ends
    }
}

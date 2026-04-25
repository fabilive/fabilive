<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\Category;
use App\Models\Childcategory;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Gallery;
use App\Models\Generalsetting;
use App\Models\Product;
use App\Models\Subcategory;

use Datatables;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Image;
use Validator;
use Auth;
use App\Helpers\PriceHelper;

class ProductController extends VendorBaseController
{
    //*** JSON Request
    public function datatables()
    {
        try {
            $user = $this->user;
            $datas = $user->products()->where('product_type', 'normal')->latest('id')->get();
        } catch (\Exception $e) {
            $datas = collect();
        }

            $editColumn_price = function (Product $data) {
                $gs = $this->gs;
                $curr = $this->curr;
                $value = $curr ? $curr->value : 1;
                return $data->adminShowPrice();
            };
            return \Datatables::of($datas)
            ->editColumn('name', function (Product $data) {
                $name = mb_strlen(strip_tags($data->name), 'UTF-8') > 50 ? mb_substr(strip_tags($data->name), 0, 50, 'UTF-8').'...' : strip_tags($data->name);
                $prod_url = $data->slug ? route('front.product', $data->slug) : 'javascript:;';
                $id = '<small>'.__('Product ID').': <a href="'.$prod_url.'" '.($data->slug ? 'target="_blank"' : '').'>'.sprintf("%'.08d", $data->id).'</a></small>';

                return $name.'<br>'.$id;
            })
            ->editColumn('price', $editColumn_price)
            ->addColumn('status', function (Product $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';

                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'.route('vendor-prod-status', ['id1' => $data->id, 'id2' => 1]).'" '.$s.'>'.__('Activated').'</option><<option data-val="0" value="'.route('vendor-prod-status', ['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>'.__('Deactivated').'</option>/select></div>';
            })
            ->addColumn('action', function (Product $data) {
                return '<div class="action-list"><a href="'.route('vendor-prod-edit', $data->id).'"> <i class="fas fa-edit"></i>'.__('Edit').'</a><a href="javascript" class="set-gallery" data-toggle="modal" data-target="#setgallery"><input type="hidden" value="'.$data->id.'"><i class="fas fa-eye"></i> '.__('View Gallery').'</a><a href="javascript:;" data-href="'.route('vendor-prod-delete', $data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->rawColumns(['name', 'status', 'action'])
            ->toJson();
    }

    //*** JSON Request
    public function catalogdatatables()
    {
        try {
            $datas = Product::where('product_type', 'normal')->where('status', '=', 1)->where('is_catalog', '=', 1)->latest('id')->get();
        } catch (\Exception $e) {
            $datas = collect();
        }

        //--- Integrating This Collection Into Datatables
            $editColumn_catalog_price = function (Product $data) {
                $curr = $this->curr;
                $value = $curr ? $curr->value : 1;
                $price = round($data->price * $value, 2);

                return \PriceHelper::showAdminCurrencyPrice($price);
            };
            return \Datatables::of($datas)
            ->editColumn('name', function (Product $data) {
                $name = mb_strlen(strip_tags($data->name), 'UTF-8') > 50 ? mb_substr(strip_tags($data->name), 0, 50, 'UTF-8').'...' : strip_tags($data->name);
                $prod_url = $data->slug ? route('front.product', $data->slug) : 'javascript:;';
                $id = '<small>'.__('Product ID').': <a href="'.$prod_url.'" '.($data->slug ? 'target="_blank"' : '').'>'.sprintf("%'.08d", $data->id).'</a></small>';

                return $name.'<br>'.$id;
            })
            ->editColumn('price', $editColumn_catalog_price)
            ->addColumn('action', function (Product $data) {
                $user = $this->user;
                $ck = $user->products()->where('catalog_id', '=', $data->id)->count() > 0;
                $catalog = $ck ? '<a href="javascript:;"> '.__('Added To Catalog').'</a>' : '<a href="'.route('vendor-prod-catalog-edit', $data->id).'"><i class="fas fa-plus"></i> '.__('Add To Catalog').'</a>';

                return '<div class="action-list">'.$catalog.'</div>';
            })
            ->rawColumns(['name', 'status', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index()
    {
        return view('vendor.product.index');
    }

    public function types()
    {
        return view('vendor.product.types');
    }

    public function catalogs()
    {
        return view('vendor.product.catalogs');
    }

    public function create($slug)
    {
        $user = $this->user;
        if (($this->gs->verify_product ?? 0) == 1) {
            if (! $user->checkStatus()) {
                // Session::flash('unsuccess', __('You must complete your verfication first.'));
                Session::flash('unsuccess', __("To guarantee Trust & Security for buyers and the marketplace, and also to prevent fraud and ensure legal accountability, please provide the following requirements: 
- Business Registration Certificate [if available]
- Taxpayer Card copy or Taxpayer Identification Number (TIN) document copy [must]
- Your Valid Government ID (National ID Card copy, Passport copy, driver's license copy, Residence Permit for foreigners) [must]"));

                return redirect()->route('vendor-verify');
            }
        }
        $cats = Category::where('category_type', $slug)->get();
        $countries = Country::where('status', 1)->get();
        $sign = $this->curr;

        $cities = City::where('status', 1)
            ->whereHas('state', function ($q) {
                $q->where('status', 1)
                    ->whereHas('country', function ($qq) {
                        $qq->where('status', 1);
                    });
            })
            ->orderBy('city_name')
            ->pluck('city_name', 'id');
        if ($slug == 'physical') {
            if ($this->gs->physical == 1) {
                return view('vendor.product.create.physical', compact('cats', 'countries', 'sign', 'cities'));
            } else {
                return back();
            }
        } elseif ($slug == 'digital') {
            if ($this->gs->digital == 1) {
                return view('vendor.product.create.digital', compact('cats', 'countries', 'sign', 'cities'));
            } else {
                return back();
            }
        } elseif (($slug == 'license')) {
            if ($this->gs->license == 1) {
                return view('vendor.product.create.license', compact('cats', 'countries', 'sign', 'cities'));
            } else {
                return back();
            }
        } elseif (($slug == 'listing')) {
            if ($this->gs->listing == 1) {
                return view('vendor.product.create.listing', compact('cats', 'countries', 'sign', 'cities'));
            } else {
                return back();
            }
        }
    }

    //*** GET Request
    public function status($id1, $id2)
    {
        $data = Product::findOrFail($id1);
        $data->status = $id2;
        $data->update();
        //--- Redirect Section
        $msg = __('Status Updated Successfully.');

        return response()->json($msg);
        //--- Redirect Section Ends
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
        file_put_contents(public_path($path), $image);

        $input['photo'] = $image_name;

        $data->update($input);

        return response()->json(['status' => true, 'file_name' => $image_name]);

        $img = \Image::make(public_path().'/assets/images/products/'.$data->photo)->resize(285, 285);
        $thumbnail = time().Str::random(8).'.jpg';
        $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
        $data->thumbnail = $thumbnail;
        $data->update();

        return response()->json(['status' => true, 'file_name' => $image_name]);
    }

    //*** POST Request
    public function import()
    {
        $cats = Category::all();
        $sign = $this->curr;

        return view('vendor.product.productcsv', compact('cats', 'sign'));
    }

    public function importSubmit(Request $request)
    {
        $user = $this->user;
        $package = $user->subscribes()->orderBy('id', 'desc')->first();
        $prods = $user->products()->orderBy('id', 'desc')->get()->count();
        if (($this->gs->verify_product ?? 0) == 1) {
            if (! $user->checkStatus()) {
                return response()->json(['errors' => [0 => __('You must complete your verfication first.')]]);
            }
        }
        if ($prods < $package->allowed_products || $package->allowed_products == 0) {
            $log = '';
            $rules = [
                'csvfile' => 'required|mimes:csv,txt',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
            }
            $filename = '';
            if ($file = $request->file('csvfile')) {
                $extensions = ['csv'];
                if (! in_array($file->getClientOriginalExtension(), $extensions)) {
                    return response()->json(['errors' => ['Image format not supported']]);
                }
                $filename = time().'-'.$file->getClientOriginalName();
                $file->move(public_path('assets/temp_files'), $filename);
            }
            $datas = '';
            $file = fopen(public_path('assets/temp_files/'.$filename), 'r');
            $i = 1;
            while (($line = fgetcsv($file)) !== false) {
                if ($i != 1) {
                    if (! Product::where('sku', $line[0])->exists()) {
                        $data = new Product;
                        $sign = \App\Models\Currency::where('is_default', 1)->first() ?? \App\Models\Currency::where('id', '>', 0)->first();
                        $input['type'] = 'Physical';
                        $input['sku'] = $line[0];
                        $input['category_id'] = null;
                        $input['subcategory_id'] = null;
                        $input['childcategory_id'] = null;
                        $mcat = Category::where(DB::raw('lower(name)'), strtolower($line[1]));
                        //$mcat = Category::where("name", $line[1]);
                        if ($mcat->exists()) {
                            $input['category_id'] = $mcat->first()->id;
                            if ($line[2] != '') {
                                $scat = Subcategory::where(DB::raw('lower(name)'), strtolower($line[2]));
                                if ($scat->exists()) {
                                    $input['subcategory_id'] = $scat->first()->id;
                                }
                            }
                            if ($line[3] != '') {
                                $chcat = Childcategory::where(DB::raw('lower(name)'), strtolower($line[3]));
                                if ($chcat->exists()) {
                                    $input['childcategory_id'] = $chcat->first()->id;
                                }
                            }
                            $input['photo'] = $line[5];
                            $input['name'] = $line[4];
                            $input['details'] = $line[6];
                            //                $input['category_id'] = $request->category_id;
                            //                $input['subcategory_id'] = $request->subcategory_id;
                            //                $input['childcategory_id'] = $request->childcategory_id;
                            $input['color'] = $line[13];
                            $input['price'] = $line[7];
                            $input['previous_price'] = $line[8] != '' ? $line[8] : null;
                            $input['stock'] = $line[9];
                            $input['size'] = $line[10];
                            $input['size_qty'] = $line[11];
                            $input['size_price'] = $line[12];
                            $input['youtube'] = $line[15];
                            $input['policy'] = $line[16];
                            $input['meta_tag'] = $line[17];
                            $input['meta_description'] = $line[18];
                            $input['tags'] = $line[14];
                            $input['product_type'] = $line[19];
                            $input['affiliate_link'] = $line[20];
                            
                            // New Advanced E-commerce Variables
                            $input['delivery_fee'] = isset($line[21]) && $line[21] != '' ? $line[21] : 0;
                            $input['delivery_unit'] = isset($line[22]) && $line[22] != '' ? $line[22] : null;
                            $input['product_location'] = isset($line[23]) && $line[23] != '' ? $line[23] : null;
                            $input['product_condition'] = isset($line[24]) && $line[24] != '' ? $line[24] : 0;
                            $input['minimum_qty'] = isset($line[25]) && $line[25] != '' ? $line[25] : null;
                            $input['measure'] = isset($line[26]) && $line[26] != '' ? $line[26] : null;
                            $input['discount_date_start'] = isset($line[27]) && $line[27] != '' ? $line[27] : null;
                            $input['discount_date_end'] = isset($line[28]) && $line[28] != '' ? $line[28] : null;
                            $input['cross_products'] = isset($line[29]) && $line[29] != '' ? $line[29] : null;
                            $input['ship'] = isset($line[30]) && $line[30] != '' ? $line[30] : null;
                            $input['slug'] = Str::slug($input['name'], '-').'-'.strtolower($input['sku']);
                            $image_url = $line[5];
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_URL, $image_url);
                            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
                            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
                            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                            curl_setopt($ch, CURLOPT_HEADER, true);
                            curl_setopt($ch, CURLOPT_NOBODY, true);
                            $content = curl_exec($ch);
                            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                            $thumb_url = '';
                            if (strpos($contentType, 'image/') !== false) {
                                $fimg = \Image::make($line[5])->resize(800, 800);
                                $fphoto = time().Str::random(8).'.jpg';
                                $fimg->save(public_path().'/assets/images/products/'.$fphoto);
                                $input['photo'] = $fphoto;
                                $thumb_url = $line[5];
                            } else {
                                $fimg = \Image::make(public_path().'/assets/images/noimage.png')->resize(800, 800);
                                $fphoto = time().Str::random(8).'.jpg';
                                $fimg->save(public_path().'/assets/images/products/'.$fphoto);
                                $input['photo'] = $fphoto;
                                $thumb_url = public_path().'/assets/images/noimage.png';
                            }
                            $timg = \Image::make($thumb_url)->resize(285, 285);
                            $thumbnail = time().Str::random(8).'.jpg';
                            $timg->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
                            $input['thumbnail'] = $thumbnail;
                            $input['price'] = ($input['price'] / $sign->value);
                            $input['previous_price'] = ($input['previous_price'] / $sign->value);
                            $input['user_id'] = $user->id;
                            // Save Data
                            $data->fill($input)->save();
                        } else {
                            $log .= '<br>'.__('Row No').': '.$i.' - '.__('No Category Found!').'<br>';
                        }
                    } else {
                        $log .= '<br>'.__('Row No').': '.$i.' - '.__('Duplicate Product Code!').'<br>';
                    }
                }

                $i++;
            }
            fclose($file);
            $msg = __('New Product Added Successfully.').$log;

            return response()->json($msg);
        } else {
            return response()->json(['errors' => [0 => 'You Can\'t Add More Products.']]);
        }
    }

    //*** POST Request
    public function store(Request $request)
    {
        \Log::info('Vendor Product Store Entry. Size: ' . $request->server('CONTENT_LENGTH') . ' bytes');
        if (! function_exists('imagecreatefromjpeg')) {
            return response()->json(['errors' => [0 => 'The server is missing the GD PHP extension with JPEG support. Please contact support or enable php-gd in your hosting panel.']]);
        }
        try {
            @ini_set('memory_limit', '512M');
            @set_time_limit(300);
            \Log::debug('Vendor Product Store: Starting process for user ID: ' . (Auth::user()->id ?? 'unknown'));
            
            $image_name = null;
            $user = $this->user;
            $package = $user->subscribes()->latest('id')->first();
            $prods = $user->products()->latest('id')->get()->count();
            if (($this->gs->verify_product ?? 0) == 1) {
                if (! $user->checkStatus()) {
                    return response()->json(['errors' => [0 => __('You must complete your verfication first.')]]);
                }
            }
            if (! $package || $package->allowed_products == 0 || $prods < $package->allowed_products) {
                $rules = [
                    'photo' => 'required',
                    'file' => 'mimes:zip,rar,7z,pdf,doc,docx,xls,xlsx,txt,mp4,mov,avi,webm,webp,svg,gif,jfif',
                    'discount_date_start' => 'required|date|after_or_equal:today',
                    'discount_date_end' => 'nullable|date|after:discount_date_start',
                ];

                $customMessages = [
                    'discount_date_start.required' => __('Discount Start Date is compulsory.'),
                    'discount_date_start.after_or_equal' => __('Discount Start Date cannot be in the past.'),
                    'discount_date_end.after' => __('Discount End Date must be after the Start Date.'),
                ];

                $validator = Validator::make($request->all(), $rules, $customMessages);
                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
                }

                // Price Validation: Sale Price < Regular Price
                if ($request->previous_price && $request->price >= $request->previous_price) {
                    return response()->json(['errors' => [0 => __('Sale Price must be lower than the Regular Price.')]]);
                }

                // Pre-validate Gallery extensions to prevent ghost products
                if ($files = $request->file('gallery')) {
                    foreach ($files as $file) {
                        $extensions = ['jpeg', 'jpg', 'png', 'jfif', 'webp', 'svg', 'gif', 'mp4', 'mov', 'avi', 'webm', 'pdf', 'docx', 'xlsx', 'zip'];
                        if (! in_array(strtolower($file->getClientOriginalExtension()), $extensions)) {
                            return response()->json(['errors' => ['File format not supported: '.$file->getClientOriginalExtension()]]);
                        }
                    }
                }

                \Log::debug('Vendor Product Store: Basic validation passed. Starting transaction.');
                DB::beginTransaction();
                $data = new Product;
                $sign = $this->curr;
                $input = $request->all();
                if ($file = $request->file('file')) {
                    $extensions = ['zip', 'rar', '7z', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'mp4', 'mov', 'avi', 'webm', 'webp', 'svg', 'gif', 'jfif'];
                    if (! in_array($file->getClientOriginalExtension(), $extensions)) {
                        return response()->json(['errors' => ['File format not supported']]);
                    }
                    $name = \PriceHelper::ImageCreateName($file);
                    $file->move(public_path('assets/files'), $name);

                    $input['file'] = $name;
                }
                if (! empty($request->photo)) {
                    $image = $request->photo;
                    if (strpos($image, ';') !== false && strpos($image, ',') !== false) {
                        [$type, $image] = explode(';', $image);
                        [, $image] = explode(',', $image);
                        $image = base64_decode($image);
                        $image_name = time().Str::random(8).'.png';
                        $path = 'assets/images/products/'.$image_name;
                        $directory = public_path('assets/images/products');
                        if (!file_exists($directory)) {
                            mkdir($directory, 0755, true);
                        }
                        file_put_contents(public_path($path), $image);
                        $input['photo'] = $image_name;
                    }
                }

                // Check if post_max_size or upload_max_filesize was exceeded (resulting in missing data)
                if (empty($input['name']) && $request->isMethod('post')) {
                    return response()->json(['errors' => [0 => 'The server received an empty request. This usually happens if the upload size exceeds server limits (currently 2MB/8MB). Please try a smaller file.']]);
                }
                $input['thumbnail'] = $image_name ?? 'noimage.png'; // Fallback
                if ($request->type == 'Physical' || $request->type == 'Listing') {
                    $rules = ['sku' => 'min:8|unique:products'];
                    $validator = Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                        return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
                    }
                    if ($request->product_condition_check == '') {
                        $input['product_condition'] = 0;
                    }
                    if ($request->preordered_check == '') {
                        $input['preordered'] = 0;
                    }
                    if ($request->minimum_qty_check == '') {
                        $input['minimum_qty'] = null;
                    }
                    if ($request->shipping_time_check == '') {
                        $input['ship'] = null;
                    }
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
                            $input['color'] = implode(',', (array)$request->colors);
                            $input['size'] = implode(',', (array)$request->size);
                            $input['size_qty'] = implode(',', (array)$request->size_qty);
                            $size_prices = $request->size_price;
                            $s_price = [];
                            foreach ($size_prices as $key => $sPrice) {
                                $s_price[$key] = $sPrice / $sign->value;
                            }
                            $input['size_price'] = implode(',', $s_price);
                        }
                    }
                    if (empty($request->color_check)) {
                        $input['color_all'] = null;
                    } else {
                        $input['color_all'] = implode(',', (array)$request->color_all);
                    }
                    if (empty($request->size_check)) {
                        $input['size_all'] = null;
                    } else {
                        if (is_array($request->size_all)) {
                            $input['size_all'] = implode(',', $request->size_all);
                        } else {
                            $input['size_all'] = $request->size_all;
                        }
                    }
                    if (empty($request->whole_check)) {
                        $input['whole_sell_qty'] = null;
                        $input['whole_sell_discount'] = null;
                    } else {
                        if (in_array(null, $request->whole_sell_qty) || in_array(null, $request->whole_sell_discount)) {
                            $input['whole_sell_qty'] = null;
                            $input['whole_sell_discount'] = null;
                        } else {
                            $input['whole_sell_qty'] = implode(',', $request->whole_sell_qty);
                            $input['whole_sell_discount'] = implode(',', $request->whole_sell_discount);
                        }
                    }
                    if (empty($request->color_check)) {
                        $input['color'] = null;
                    } else {
                        $input['color'] = implode(',', (array)$request->colors);
                    }
                    if ($request->mesasure_check == '') {
                        $input['measure'] = null;
                    }
                }
                if (empty($request->seo_check)) {
                    $input['meta_tag'] = null;
                    $input['meta_description'] = null;
                } else {
                    if (! empty($request->meta_tag)) {
                        $input['meta_tag'] = implode(',', $request->meta_tag);
                    }
                }
                if ($request->type == 'License') {
                    if (in_array(null, $request->license) || in_array(null, $request->license_qty)) {
                        $input['license'] = null;
                        $input['license_qty'] = null;
                    } else {
                        $input['license'] = implode(',,', $request->license);
                        $input['license_qty'] = implode(',', $request->license_qty);
                    }
                }
                if (in_array(null, (array)$request->features) || in_array(null, (array)$request->colors)) {
                    $input['features'] = null;
                    $input['colors'] = null;
                } else {
                    $input['features'] = implode(',', str_replace(',', ' ', (array)$request->features));
                    $input['colors'] = implode(',', str_replace(',', ' ', (array)$request->colors));
                }
                if (! empty($request->tags)) {
                    $input['tags'] = implode(',', $request->tags);
                }
                $input['price'] = $input['price'];
                $input['previous_price'] = $input['previous_price'];
                $input['user_id'] = $this->user->id;
                $attrArr = [];
                if (! empty($request->category_id)) {
                    $catAttrs = Attribute::where('attributable_id', $request->category_id)->where('attributable_type', 'App\Models\Category')->get();
                    if (! empty($catAttrs)) {
                        foreach ($catAttrs as $key => $catAttr) {
                            $in_name = $catAttr->input_name;
                            if ($request->has("$in_name")) {
                                $attrArr["$in_name"]['values'] = $request["$in_name"];
                                foreach ((array)$request["$in_name".'_price'] as $aprice) {
                                    $ttt["$in_name".'_price'][] = (float)$aprice / $sign->value;
                                }
                                $attrArr["$in_name"]['prices'] = $ttt["$in_name".'_price'];
                                if ($catAttr->details_status) {
                                    $attrArr["$in_name"]['details_status'] = 1;
                                } else {
                                    $attrArr["$in_name"]['details_status'] = 0;
                                }
                            }
                        }
                    }
                }
                if (! empty($request->subcategory_id)) {
                    $subAttrs = Attribute::where('attributable_id', $request->subcategory_id)->where('attributable_type', 'App\Models\Subcategory')->get();
                    if (! empty($subAttrs)) {
                        foreach ($subAttrs as $key => $subAttr) {
                            $in_name = $subAttr->input_name;
                            if ($request->has("$in_name")) {
                                $attrArr["$in_name"]['values'] = $request["$in_name"];
                                foreach ((array)$request["$in_name".'_price'] as $aprice) {
                                    $ttt["$in_name".'_price'][] = (float)$aprice / $sign->value;
                                }
                                $attrArr["$in_name"]['prices'] = $ttt["$in_name".'_price'];
                                if ($subAttr->details_status) {
                                    $attrArr["$in_name"]['details_status'] = 1;
                                } else {
                                    $attrArr["$in_name"]['details_status'] = 0;
                                }
                            }
                        }
                    }
                }
                if (! empty($request->childcategory_id)) {
                    $childAttrs = Attribute::where('attributable_id', $request->childcategory_id)->where('attributable_type', 'App\Models\Childcategory')->get();
                    if (! empty($childAttrs)) {
                        foreach ($childAttrs as $key => $childAttr) {
                            $in_name = $childAttr->input_name;
                            if ($request->has("$in_name")) {
                                $attrArr["$in_name"]['values'] = $request["$in_name"];
                                foreach ((array)$request["$in_name".'_price'] as $aprice) {
                                    $ttt["$in_name".'_price'][] = (float)$aprice / $sign->value;
                                }
                                $attrArr["$in_name"]['prices'] = $ttt["$in_name".'_price'];
                                if ($childAttr->details_status) {
                                    $attrArr["$in_name"]['details_status'] = 1;
                                } else {
                                    $attrArr["$in_name"]['details_status'] = 0;
                                }
                            }
                        }
                    }
                }
                if (empty($attrArr)) {
                    $input['attributes'] = null;
                } else {
                    $jsonAttr = json_encode($attrArr);
                    $input['attributes'] = $jsonAttr;
                }
                $input['product_location'] = $request->product_location;
                $input['product_city'] = $request->product_city;
                $input['delivery_fee'] = $request->delivery_fee;
                $input['delivery_unit'] = $request->delivery_unit;
                if (isset($input['license'])) {
                    if (is_array($input['license'])) {
                        $input['license'] = implode(', ', $input['license']);
                    } else {
                        $input['license'] = $input['license'];
                    }
                }
                if (isset($input['license_qty'])) {
                    if (is_array($input['license_qty'])) {
                        $input['license_qty'] = implode(', ', $input['license_qty']);
                    } else {
                        $input['license_qty'] = $input['license_qty'];
                    }
                }
                $data->fill($input)->save();
                \Log::debug('Vendor Product Store: Product model saved. ID: ' . $data->id);
                $prod = Product::find($data->id);
                if ($prod->type != 'Physical') {
                    $prod->slug = Str::slug($data->name, '-').'-'.strtolower(Str::random(3).$data->id.Str::random(3));
                } else {
                    $prod->slug = Str::slug($data->name, '-').'-'.strtolower($data->sku);
                }
                // Safeguard main photo resizing
                $main_photo_path = public_path().'/assets/images/products/'.$prod->photo;
                $thumb_dir = public_path().'/assets/images/thumbnails';
                if (!file_exists($thumb_dir)) {
                    mkdir($thumb_dir, 0755, true);
                }
                if (file_exists($main_photo_path)) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime = finfo_file($finfo, $main_photo_path);
                    finfo_close($finfo);

                    if (strpos($mime, 'image/') === 0) {
                        try {
                            $img = \Image::make($main_photo_path)->resize(285, 285);
                            $thumbnail = time().Str::random(8).'.jpg';
                            $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
                            $prod->thumbnail = $thumbnail;
                        } catch (\Exception $e) {
                            \Log::warning('Vendor Product Store: Thumbnail generation failed: ' . $e->getMessage());
                            $prod->thumbnail = 'noimage.png';
                        }
                    } else {
                        $prod->thumbnail = 'noimage.png'; // Or some default
                    }
                }
                $prod->update();

                $lastid = $data->id;
                $gallery_dir = public_path().'/assets/images/galleries';
                if (!file_exists($gallery_dir)) {
                    mkdir($gallery_dir, 0755, true);
                }
                if ($files = $request->file('gallery')) {
                    foreach ($files as $key => $file) {
                        $extensions = ['jpeg', 'jpg', 'png', 'svg', 'webp', 'gif', 'jfif', 'mp4', 'mov', 'avi', 'webm', 'pdf', 'docx', 'xlsx', 'zip'];
                        if (! in_array(strtolower($file->getClientOriginalExtension()), $extensions)) {
                            // This should already be caught by pre-validation, but added for safety
                            DB::rollBack();
                            return response()->json(['errors' => ['File format not supported: '.$file->getClientOriginalExtension()]]);
                        }
                        if (is_array($request->galval) && in_array($key, $request->galval)) {
                            $gallery = new Gallery;
                            $name = \PriceHelper::ImageCreateName($file);
                            
                            $is_image = in_array(strtolower($file->getClientOriginalExtension()), ['jpeg', 'jpg', 'png', 'svg', 'webp', 'gif', 'jfif']);
                            if ($is_image) {
                                try {
                                    $img = \Image::make($file->getRealPath())->resize(800, 800);
                                    $img->save(public_path().'/assets/images/galleries/'.$name);
                                } catch (\Exception $e) {
                                    \Log::warning('Vendor Product Store: Gallery image processing failed: ' . $e->getMessage());
                                    $file->move(public_path().'/assets/images/galleries/', $name);
                                }
                            } else {
                                $file->move(public_path().'/assets/images/galleries/', $name);
                            }

                            $gallery['photo'] = $name;
                            $gallery['product_id'] = $lastid;
                            $gallery->save();
                        }
                    }
                }
                \Log::debug('Vendor Product Store: All processing complete. Committing transaction.');
                DB::commit();
                $msg = __('New Product Added Successfully.').'<a href="'.route('vendor-prod-index').'">'.__('View Product Lists.').'</a>';

                return response()->json($msg);
            } else {
                return response()->json(['errors' => [0 => __('You Can\'t Add More Product.')]]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            \Log::error('Vendor Product Store Error: '.$e->getMessage().' in ' . $e->getFile() . ' on line ' . $e->getLine());
            \Log::error('Stack Trace: ' . $e->getTraceAsString());

            return response()->json(['errors' => [0 => 'Server Error: '.$e->getMessage() . ' (Line: ' . $e->getLine() . ')']], 500);
        }
    }

    //*** GET Request
    public function edit($id)
    {
        $cats = Category::all();
        $data = Product::findOrFail($id);
        $sign = $this->curr ?? \App\Models\Currency::where('is_default', 1)->first() ?? \App\Models\Currency::where('id', '>', 0)->first();

        $cities = City::where('status', 1)
            ->whereHas('state', function ($q) {
                $q->where('status', 1)
                    ->whereHas('country', function ($qq) {
                        $qq->where('status', 1);
                    });
            })
            ->orderBy('city_name')
            ->pluck('city_name', 'id');

        if ($data->type == 'Digital') {
            return view('vendor.product.edit.digital', compact('cats', 'data', 'sign', 'cities'));
        } elseif ($data->type == 'License') {
            return view('vendor.product.edit.license', compact('cats', 'data', 'sign', 'cities'));
        } elseif ($data->type == 'Listing') {
            return view('vendor.product.edit.listing', compact('cats', 'data', 'sign', 'cities'));
        } else {
            return view('vendor.product.edit.physical', compact('cats', 'data', 'sign', 'cities'));
        }
    }

    //*** GET Request CATALOG
    public function catalogedit($id)
    {
        $cats = Category::all();
        $data = Product::findOrFail($id);
        $sign = $this->curr ?? \App\Models\Currency::where('is_default', 1)->first() ?? \App\Models\Currency::where('id', '>', 0)->first();

        if ($data->type == 'Digital') {
            return view('vendor.product.edit.catalog.digital', compact('cats', 'data', 'sign'));
        } elseif ($data->type == 'License') {
            return view('vendor.product.edit.catalog.license', compact('cats', 'data', 'sign'));
        } else {
            return view('vendor.product.edit.catalog.physical', compact('cats', 'data', 'sign'));
        }
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        $rules = [
            'file' => 'mimes:zip',
            'discount_date_start' => 'required|date|after_or_equal:today',
            'discount_date_end' => 'nullable|date|after:discount_date_start',
        ];

        $customMessages = [
            'discount_date_start.required' => __('Discount Start Date is compulsory.'),
            'discount_date_start.after_or_equal' => __('Discount Start Date cannot be in the past.'),
            'discount_date_end.after' => __('Discount End Date must be after the Start Date.'),
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        // Price Validation: Sale Price < Regular Price
        if ($request->previous_price && $request->price >= $request->previous_price) {
            return response()->json(['errors' => [0 => __('Sale Price must be lower than the Regular Price.')]]);
        }
        $data = Product::findOrFail($id);
        $sign = $this->curr ?? \App\Models\Currency::where('is_default', 1)->first() ?? \App\Models\Currency::where('id', '>', 0)->first();
        $input = $request->all();
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
        if ($data->type == 'Physical') {
            $rules = ['sku' => 'min:8|unique:products,sku,'.$id];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
            }
            if ($request->product_condition_check == '') {
                $input['product_condition'] = 0;
            }
            if ($request->preordered_check == '') {
                $input['preordered'] = 0;
            }
            if ($request->minimum_qty_check == '') {
                $input['minimum_qty'] = null;
            }
            if ($request->shipping_time_check == '') {
                $input['ship'] = null;
            }
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
            if (empty($request->color_check)) {
                $input['color_all'] = null;
            } else {
                $input['color_all'] = implode(',', $request->color_all);
            }
            // if (empty($request->size_check)) {
            //     $input['size_all'] = null;
            // } else {
            //     $input['size_all'] = implode(',', $request->size_all);
            // }
            if (empty($request->whole_check)) {
                $input['whole_sell_qty'] = null;
                $input['whole_sell_discount'] = null;
            } else {
                if (in_array(null, $request->whole_sell_qty) || in_array(null, $request->whole_sell_discount)) {
                    $input['whole_sell_qty'] = null;
                    $input['whole_sell_discount'] = null;
                } else {
                    $input['whole_sell_qty'] = implode(',', $request->whole_sell_qty);
                    $input['whole_sell_discount'] = implode(',', $request->whole_sell_discount);
                }
            }
            if (empty($request->color_check)) {
                $input['color'] = null;
            } else {
                if (! empty($request->color)) {
                    $input['color'] = implode(',', $request->color);
                }
                if (empty($request->color)) {
                    $input['color'] = null;
                }
            }
            if ($request->measure_check == '') {
                $input['measure'] = null;
            }
        }
        if (empty($request->seo_check)) {
            $input['meta_tag'] = null;
            $input['meta_description'] = null;
        } else {
            if (! empty($request->meta_tag)) {
                $input['meta_tag'] = implode(',', $request->meta_tag);
            }
        }
        if ($data->type == 'License') {
            if (! in_array(null, $request->license) && ! in_array(null, $request->license_qty)) {
                $input['license'] = implode(',,', $request->license);
                $input['license_qty'] = implode(',', $request->license_qty);
            } else {
                if (in_array(null, $request->license) || in_array(null, $request->license_qty)) {
                    $input['license'] = null;
                    $input['license_qty'] = null;
                } else {
                    $license = explode(',,', $data->license);
                    $license_qty = explode(',', $data->license_qty);
                    $input['license'] = implode(',,', $license);
                    $input['license_qty'] = implode(',', $license_qty);
                }
            }
        }
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
        if (! empty($request->tags)) {
            $input['tags'] = implode(',', $request->tags);
        }
        if (empty($request->tags)) {
            $input['tags'] = null;
        }
        $input['price'] = $input['price'] / $sign->value;
        $input['previous_price'] = $input['previous_price'] / $sign->value;
        $attrArr = [];
        if (! empty($request->category_id)) {
            $catAttrs = Attribute::where('attributable_id', $request->category_id)->where('attributable_type', 'App\Models\Category')->get();
            if (! empty($catAttrs)) {
                foreach ($catAttrs as $key => $catAttr) {
                    $in_name = $catAttr->input_name;
                    if ($request->has("$in_name")) {
                        $attrArr["$in_name"]['values'] = $request["$in_name"];
                        foreach ($request["$in_name".'_price'] as $aprice) {
                            $ttt["$in_name".'_price'][] = $aprice / $sign->value;
                        }
                        $attrArr["$in_name"]['prices'] = $ttt["$in_name".'_price'];
                        if ($catAttr->details_status) {
                            $attrArr["$in_name"]['details_status'] = 1;
                        } else {
                            $attrArr["$in_name"]['details_status'] = 0;
                        }
                    }
                }
            }
        }
        if (! empty($request->subcategory_id)) {
            $subAttrs = Attribute::where('attributable_id', $request->subcategory_id)->where('attributable_type', 'App\Models\Subcategory')->get();
            if (! empty($subAttrs)) {
                foreach ($subAttrs as $key => $subAttr) {
                    $in_name = $subAttr->input_name;
                    if ($request->has("$in_name")) {
                        $attrArr["$in_name"]['values'] = $request["$in_name"];
                        foreach ($request["$in_name".'_price'] as $aprice) {
                            $ttt["$in_name".'_price'][] = $aprice / $sign->value;
                        }
                        $attrArr["$in_name"]['prices'] = $ttt["$in_name".'_price'];
                        if ($subAttr->details_status) {
                            $attrArr["$in_name"]['details_status'] = 1;
                        } else {
                            $attrArr["$in_name"]['details_status'] = 0;
                        }
                    }
                }
            }
        }
        if (! empty($request->childcategory_id)) {
            $childAttrs = Attribute::where('attributable_id', $request->childcategory_id)->where('attributable_type', 'App\Models\Childcategory')->get();
            if (! empty($childAttrs)) {
                foreach ($childAttrs as $key => $childAttr) {
                    $in_name = $childAttr->input_name;
                    if ($request->has("$in_name")) {
                        $attrArr["$in_name"]['values'] = $request["$in_name"];
                        foreach ($request["$in_name".'_price'] as $aprice) {
                            $ttt["$in_name".'_price'][] = $aprice / $sign->value;
                        }
                        $attrArr["$in_name"]['prices'] = $ttt["$in_name".'_price'];
                        if ($childAttr->details_status) {
                            $attrArr["$in_name"]['details_status'] = 1;
                        } else {
                            $attrArr["$in_name"]['details_status'] = 0;
                        }
                    }
                }
            }
        }
        if (empty($attrArr)) {
            $input['attributes'] = null;
        } else {
            $jsonAttr = json_encode($attrArr);
            $input['attributes'] = $jsonAttr;
        }
        $data->slug = Str::slug($data->name, '-').'-'.strtolower($data->sku);
        $input['product_location'] = $request->product_location;
        $input['product_city'] = $request->product_city;
        $input['delivery_fee'] = $request->delivery_fee;
        $input['delivery_unit'] = $request->delivery_unit;
        $data->update($input);
        $msg = __('Product Updated Successfully.').'<a href="'.route('vendor-prod-index').'">'.__('View Product Lists.').'</a>';

        return response()->json($msg);
    }

    //*** POST Request CATALOG
    public function catalogupdate(Request $request, $id)
    {

        $user = $this->user;
        $package = $user->subscribes()->latest('id')->first();
        $prods = $user->products()->latest('id')->get()->count();
        if (($this->gs->verify_product ?? 0) == 1) {
            if (! $user->checkStatus()) {
                return response()->json(['errors' => [0 => __('You must complete your verfication first.')]]);
            }
        }
        if ($prods < $package->allowed_products || $package->allowed_products == 0) {

            //--- Validation Section
            $rules = [
                'file' => 'mimes:zip',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
            }
            //--- Validation Section Ends

            //--- Logic Section
            $data = new Product;
            $sign = $this->curr ?? \App\Models\Currency::where('is_default', 1)->first() ?? \App\Models\Currency::where('id', '>', 0)->first();
            $input = $request->all();
            // Check File
            if ($file = $request->file('file')) {
                $extensions = ['zip'];
                if (! in_array($file->getClientOriginalExtension(), $extensions)) {
                    return response()->json(['errors' => ['Image format not supported']]);
                }
                $name = \PriceHelper::ImageCreateName($file);
                $file->move(public_path('assets/files'), $name);
                $input['file'] = $name;
            }

            $image = $request->photo;
            if ($request->is_photo == '1') {
                [$type, $image] = explode(';', $image);
                [, $image] = explode(',', $image);
                $image = base64_decode($image);
                $image_name = time().Str::random(8).'.png';
                $path = 'assets/images/products/'.$image_name;
                file_put_contents(public_path($path), $image);
            } else {
                $image_name = $request->photo;
            }

            $input['photo'] = $image_name;

            // Check Physical
            if ($request->type == 'Physical') {

                //--- Validation Section
                $rules = ['sku' => 'min:8|unique:products'];

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

                // Check Minimum Qty
                if ($request->minimum_qty_check == '') {
                    $input['minimum_qty'] = null;
                }

                // Check Shipping Time
                if ($request->shipping_time_check == '') {
                    $input['ship'] = null;
                }

                // Check Size
                if (empty($request->size_check)) {
                    $input['size'] = null;
                    $input['size_qty'] = null;
                    $input['size_price'] = null;
                } else {
                    if (in_array(null, $request->size) || in_array(null, $request->size_qty)) {
                        $input['size'] = null;
                        $input['size_qty'] = null;
                        $input['size_price'] = null;
                    } else {
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

                // Check Whole Sale
                if (empty($request->whole_check)) {
                    $input['whole_sell_qty'] = null;
                    $input['whole_sell_discount'] = null;
                } else {
                    if (in_array(null, $request->whole_sell_qty) || in_array(null, $request->whole_sell_discount)) {
                        $input['whole_sell_qty'] = null;
                        $input['whole_sell_discount'] = null;
                    } else {
                        $input['whole_sell_qty'] = implode(',', $request->whole_sell_qty);
                        $input['whole_sell_discount'] = implode(',', $request->whole_sell_discount);
                    }
                }

                // Check Color
                if (empty($request->color_check)) {
                    $input['color'] = null;
                } else {
                    $input['color'] = implode(',', $request->color);
                }

                // Check Measurement
                if ($request->mesasure_check == '') {
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

            if ($request->type == 'License') {

                if (in_array(null, $request->license) || in_array(null, $request->license_qty)) {
                    $input['license'] = null;
                    $input['license_qty'] = null;
                } else {
                    $input['license'] = implode(',,', $request->license);
                    $input['license_qty'] = implode(',', $request->license_qty);
                }
            }

            // Check Features
            if (in_array(null, $request->features) || in_array(null, $request->colors)) {
                $input['features'] = null;
                $input['colors'] = null;
            } else {
                $input['features'] = implode(',', str_replace(',', ' ', $request->features));
                $input['colors'] = implode(',', str_replace(',', ' ', $request->colors));
            }

            //tags
            if (! empty($request->tags)) {
                $input['tags'] = implode(',', $request->tags);
            }

            // Conert Price According to Currency
            $input['price'] = $input['price'];
            $input['previous_price'] = $input['previous_price'];
            $input['user_id'] = $this->user->id;

            // store filtering attributes for physical product
            $attrArr = [];
            if (! empty($request->category_id)) {
                $catAttrs = Attribute::where('attributable_id', $request->category_id)->where('attributable_type', 'App\Models\Category')->get();
                if (! empty($catAttrs)) {
                    foreach ($catAttrs as $key => $catAttr) {
                        $in_name = $catAttr->input_name;
                        if ($request->has("$in_name")) {
                            $attrArr["$in_name"]['values'] = $request["$in_name"];
                            foreach ($request["$in_name".'_price'] as $aprice) {
                                $ttt["$in_name".'_price'][] = $aprice / $sign->value;
                            }
                            $attrArr["$in_name"]['prices'] = $ttt["$in_name".'_price'];
                            if ($catAttr->details_status) {
                                $attrArr["$in_name"]['details_status'] = 1;
                            } else {
                                $attrArr["$in_name"]['details_status'] = 0;
                            }
                        }
                    }
                }
            }

            if (! empty($request->subcategory_id)) {
                $subAttrs = Attribute::where('attributable_id', $request->subcategory_id)->where('attributable_type', 'App\Models\Subcategory')->get();
                if (! empty($subAttrs)) {
                    foreach ($subAttrs as $key => $subAttr) {
                        $in_name = $subAttr->input_name;
                        if ($request->has("$in_name")) {
                            $attrArr["$in_name"]['values'] = $request["$in_name"];
                            foreach ($request["$in_name".'_price'] as $aprice) {
                                $ttt["$in_name".'_price'][] = $aprice / $sign->value;
                            }
                            $attrArr["$in_name"]['prices'] = $ttt["$in_name".'_price'];
                            if ($subAttr->details_status) {
                                $attrArr["$in_name"]['details_status'] = 1;
                            } else {
                                $attrArr["$in_name"]['details_status'] = 0;
                            }
                        }
                    }
                }
            }
            if (! empty($request->childcategory_id)) {
                $childAttrs = Attribute::where('attributable_id', $request->childcategory_id)->where('attributable_type', 'App\Models\Childcategory')->get();
                if (! empty($childAttrs)) {
                    foreach ($childAttrs as $key => $childAttr) {
                        $in_name = $childAttr->input_name;
                        if ($request->has("$in_name")) {
                            $attrArr["$in_name"]['values'] = $request["$in_name"];
                            foreach ($request["$in_name".'_price'] as $aprice) {
                                $ttt["$in_name".'_price'][] = $aprice / $sign->value;
                            }
                            $attrArr["$in_name"]['prices'] = $ttt["$in_name".'_price'];
                            if ($childAttr->details_status) {
                                $attrArr["$in_name"]['details_status'] = 1;
                            } else {
                                $attrArr["$in_name"]['details_status'] = 0;
                            }
                        }
                    }
                }
            }

            if (empty($attrArr)) {
                $input['attributes'] = null;
            } else {
                $jsonAttr = json_encode($attrArr);
                $input['attributes'] = $jsonAttr;
            }

            // Save Data
            $data->fill($input)->save();

            // Set SLug

            $prod = Product::find($data->id);
            if ($prod->type != 'Physical') {
                $prod->slug = Str::slug($data->name, '-').'-'.strtolower(Str::random(3).$data->id.Str::random(3));
            } else {
                $prod->slug = Str::slug($data->name, '-').'-'.strtolower($data->sku);
            }
            $photo = $prod->photo;
            if ($request->is_photo == '0') {
                // Set Photo
                $newimg = \Image::make(public_path().'/assets/images/products/'.$prod->photo)->resize(800, 800);
                $photo = time().Str::random(8).'.jpg';
                $newimg->save(public_path().'/assets/images/products/'.$photo);
            }

            // Set Thumbnail
            $img = \Image::make(public_path().'/assets/images/products/'.$prod->photo)->resize(285, 285);
            $thumbnail = time().Str::random(8).'.jpg';
            $img->save(public_path().'/assets/images/thumbnails/'.$thumbnail);
            $prod->thumbnail = $thumbnail;
            $prod->photo = $photo;
            $prod->update();

            // Add To Gallery If any
            $lastid = $data->id;
            if ($files = $request->file('gallery')) {
                foreach ($files as $key => $file) {
                    $extensions = ['jpeg', 'jpg', 'png', 'svg'];
                    if (! in_array($file->getClientOriginalExtension(), $extensions)) {
                        return response()->json(['errors' => ['Image format not supported']]);
                    }
                    if (in_array($key, $request->galval)) {
                        $gallery = new Gallery;
                        $name = \PriceHelper::ImageCreateName($file);
                        $img = \Image::make($file->getRealPath())->resize(800, 800);
                        $thumbnail = time().Str::random(8).'.jpg';
                        $img->save(public_path().'/assets/images/galleries/'.$name);
                        $gallery['photo'] = $name;
                        $gallery['product_id'] = $lastid;
                        $gallery->save();
                    }
                }
            }
            //logic Section Ends

            //--- Redirect Section
            $msg = __('New Product Added Successfully.').'<a href="'.route('vendor-prod-index').'">'.__('View Product Lists.').'</a>';

            return response()->json($msg);
            //--- Redirect Section Ends
        } else {
            //--- Redirect Section
            return response()->json(['errors' => [0 => __('You Can\'t Add More Product.')]]);

            //--- Redirect Section Ends
        }
    }

    //*** GET Request
    public function destroy($id)
    {

        $data = Product::findOrFail($id);
        if ($data->galleries->count() > 0) {
            foreach ($data->galleries as $gal) {
                if (file_exists(public_path().'/assets/images/galleries/'.$gal->photo)) {
                    unlink(public_path().'/assets/images/galleries/'.$gal->photo);
                }
                $gal->delete();
            }
        }

        if ($data->ratings->count() > 0) {
            foreach ($data->ratings as $gal) {
                $gal->delete();
            }
        }
        if ($data->wishlists->count() > 0) {
            foreach ($data->wishlists as $gal) {
                $gal->delete();
            }
        }
        if ($data->clicks->count() > 0) {
            foreach ($data->clicks as $gal) {
                $gal->delete();
            }
        }
        if ($data->comments->count() > 0) {
            foreach ($data->comments as $gal) {
                if ($gal->replies->count() > 0) {
                    foreach ($gal->replies as $key) {
                        $key->delete();
                    }
                }
                $gal->delete();
            }
        }

        if (! filter_var($data->photo, FILTER_VALIDATE_URL)) {
            if (file_exists(public_path().'/assets/images/products/'.$data->photo)) {
                unlink(public_path().'/assets/images/products/'.$data->photo);
            }
        }

        if (file_exists(public_path().'/assets/images/thumbnails/'.$data->thumbnail) && $data->thumbnail != '') {
            unlink(public_path().'/assets/images/thumbnails/'.$data->thumbnail);
        }
        if ($data->file != null) {
            if (file_exists(public_path().'/assets/files/'.$data->file)) {
                unlink(public_path().'/assets/files/'.$data->file);
            }
        }
        $data->delete();
        //--- Redirect Section
        $msg = __('Product Deleted Successfully.');

        return response()->json($msg);
        //--- Redirect Section Ends

        // PRODUCT DELETE ENDS
    }

    public function getAttributes(Request $request)
    {
        $model = '';
        if ($request->type == 'category') {
            $model = 'App\Models\Category';
        } elseif ($request->type == 'subcategory') {
            $model = 'App\Models\Subcategory';
        } elseif ($request->type == 'childcategory') {
            $model = 'App\Models\Childcategory';
        }

        $attributes = Attribute::where('attributable_id', $request->id)->where('attributable_type', $model)->get();
        $attrOptions = [];
        foreach ($attributes as $key => $attribute) {
            $options = AttributeOption::where('attribute_id', $attribute->id)->get();
            $attrOptions[] = ['attribute' => $attribute, 'options' => $options];
        }

        return response()->json($attrOptions);
    }
}

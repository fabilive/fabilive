<?php

namespace App\Http\Controllers\Front;

use App\Helpers\PriceHelper;
use App\Models\Category;
use App\Models\Childcategory;
use App\Models\Currency;
use App\Models\PaymentGateway;
use App\Models\Product;
use App\Models\Report;
use App\Models\Subcategory;
use App\Services\CampayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Helpers\OrderHelper;

class CatalogController extends FrontBaseController
{
    // CATEGORIES SECTOPN
    protected $curr;

    protected $campayService;
    // CATEGORIES SECTOPN

    public function __construct(CampayService $campayService)
    {
        $this->campayService = $campayService;
        $this->gs = DB::table('generalsettings')->find(1);
        if (Session::has('currency')) {
            $this->curr = Currency::find(Session::get('currency'));
        } else {
            $this->curr = Currency::where('is_default', 1)->first();
        }
    }

    public function categories()
    {
        return view('frontend.products');
    }

    // -------------------------------- CATEGORY SECTION ----------------------------------------

    public function category(Request $request, $slug = null, $slug1 = null, $slug2 = null, $slug3 = null)
    {

        if ($request->view_check) {
            session::put('view', $request->view_check);
        }

        //   dd(session::get('view'));

        $cat = null;
        $subcat = null;
        $childcat = null;
        $flash = null;
        $minprice = $request->min;
        $maxprice = $request->max;
        $sort = $request->sort;
        $search = $request->search;
        $pageby = $request->pageby;
        if (! $this->curr) {
            $this->curr = Currency::where('is_default', 1)->first() ?? Currency::first();
        }

        $minprice = ($minprice / ($this->curr->value ?? 1));
        $maxprice = ($maxprice / ($this->curr->value ?? 1));
        $type = $request->has('type') ?? '';

        if (! empty($slug)) {
            $cat = Category::where('slug', $slug)->firstOrFail();
            $data['cat'] = $cat;
        }

        if (! empty($slug1)) {
            $subcat = Subcategory::where('slug', $slug1)->firstOrFail();
            $data['subcat'] = $subcat;
        }
        if (! empty($slug2)) {
            $childcat = Childcategory::where('slug', $slug2)->firstOrFail();
            $data['childcat'] = $childcat;
        }

        $data['latest_products'] = Product::with('user')->whereStatus(1)->whereLatest(1)
            ->whereHas('user', function ($q) {
                $q->where('is_vendor', 2);
            })
            ->withCount('ratings')
            ->withAvg('ratings', 'rating')
            ->get()
            ->chunk(4);

        $prods = Product::with('user')->when($cat, function ($query, $cat) {
            return $query->where('category_id', $cat->id);
        })
            ->when($subcat, function ($query, $subcat) {
                return $query->where('subcategory_id', $subcat->id);
            })
            ->when($type, function ($query, $type) {
                return $query->with('user')->whereStatus(1)->whereIsDiscount(1)
                    ->where('discount_date', '>=', date('Y-m-d'))
                    ->whereHas('user', function ($user) {
                        $user->where('is_vendor', 2);
                    });
            })
            ->when($childcat, function ($query, $childcat) {
                return $query->where('childcategory_id', $childcat->id);
            })
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%'.$search.'%')->orWhere('name', 'like', $search.'%');
            })
            ->when($minprice, function ($query, $minprice) {
                return $query->where('price', '>=', $minprice);
            })
            ->when($maxprice, function ($query, $maxprice) {
                return $query->where('price', '<=', $maxprice);
            })
            ->when($sort, function ($query, $sort) {
                if ($sort == 'date_desc') {
                    return $query->latest('id');
                } elseif ($sort == 'date_asc') {
                    return $query->oldest('id');
                } elseif ($sort == 'price_desc') {
                    return $query->latest('price');
                } elseif ($sort == 'price_asc') {
                    return $query->oldest('price');
                }
            })
            ->when(empty($sort), function ($query, $sort) {
                return $query->latest('id');
            })
            ->withCount('ratings')
            ->withAvg('ratings', 'rating');

        $prods = $prods->where(function ($query) use ($cat, $subcat, $childcat, $request) {
            $flag = 0;
            if (! empty($cat)) {
                foreach ($cat->attributes as $key => $attribute) {
                    $inname = $attribute->input_name;
                    $chFilters = $request["$inname"];

                    if (! empty($chFilters)) {
                        $flag = 1;
                        foreach ($chFilters as $key => $chFilter) {
                            if ($key == 0) {
                                $query->where('attributes', 'like', '%'.'"'.$chFilter.'"'.'%');
                            } else {
                                $query->orWhere('attributes', 'like', '%'.'"'.$chFilter.'"'.'%');
                            }
                        }
                    }
                }
            }

            if (! empty($subcat)) {
                foreach ($subcat->attributes as $attribute) {
                    $inname = $attribute->input_name;
                    $chFilters = $request["$inname"];

                    if (! empty($chFilters)) {
                        $flag = 1;
                        foreach ($chFilters as $key => $chFilter) {
                            if ($key == 0 && $flag == 0) {
                                $query->where('attributes', 'like', '%'.'"'.$chFilter.'"'.'%');
                            } else {
                                $query->orWhere('attributes', 'like', '%'.'"'.$chFilter.'"'.'%');
                            }
                        }
                    }
                }
            }

            if (! empty($childcat)) {
                foreach ($childcat->attributes as $attribute) {
                    $inname = $attribute->input_name;
                    $chFilters = $request["$inname"];

                    if (! empty($chFilters)) {
                        $flag = 1;
                        foreach ($chFilters as $key => $chFilter) {
                            if ($key == 0 && $flag == 0) {
                                $query->where('attributes', 'like', '%'.'"'.$chFilter.'"'.'%');
                            } else {
                                $query->orWhere('attributes', 'like', '%'.'"'.$chFilter.'"'.'%');
                            }
                        }
                    }
                }
            }
        });

        $prods = $prods->where('status', 1)->paginate(isset($pageby) && $pageby > 0 ? $pageby : ($this->gs->page_count > 0 ? $this->gs->page_count : 12));

        $prods->getCollection()->transform(function ($item) {
            $item->price = $item->vendorSizePrice();

            return $item;
        });
        $data['prods'] = $prods;
        //    dd($data['prods']);
        if ($request->ajax()) {
            $data['ajax_check'] = 1;

            return view('frontend.ajax.category', $data);
        }

        return view('frontend.products', $data);
    }

    public function getsubs(Request $request)
    {
        $category = Category::where('slug', $request->category)->firstOrFail();
        $subcategories = Subcategory::where('category_id', $category->id)->get();

        return $subcategories;
    }

    public function report(Request $request)
    {

        //--- Validation Section
        $rules = [
            'note' => 'max:400',
        ];
        $customs = [
            'note.max' => 'Note Must Be Less Than 400 Characters.',
        ];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Report;
        $input = $request->all();
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section
        $msg = 'New Data Added Successfully.';

        return response()->json($msg);
        //--- Redirect Section Ends

    }

    public function pay(Request $request)
    {
        $request->validate([
            'phoneNumber' => ['required', 'regex:/^(\+237|0)?(6[7-9]|68)[0-9]{7}$/'],
        ]);

        $input = $request->all();
        $data = PaymentGateway::whereKeyword('razorpay')->first();
        $total = $request->total;

        if ($request->currency_name != 'CFA') {
            return redirect()->back()->with('unsuccess', __('Please Select INR Currency For This Payment.'));
        }

        if ($request->pass_check) {
            $auth = OrderHelper::auth_check($input); // For Authentication Checking
            if (! $auth['auth_success']) {
                return redirect()->back()->with('unsuccess', $auth['error_message']);
            }
        }

        if (! Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', __("You don't have any product to checkout."));
        }

        $order['item_name'] = $this->gs->title.' Order';
        $order['item_number'] = Str::random(4).time();
        $order['item_amount'] = round($total, 2);
        $cancel_url = route('front.payment.cancle');
        $notify_url = route('front.razorpay.notify');

        //$total = PriceHelper::getOrderTotalAmount($input, Session::get('cart'));

        Session::put('input_data', $input);
        $total = intval(ceil($total));
        //dd($total);

        $response = $this->campayService->requestPayment(
            $total,
            $request->phoneNumber,
            $request->personal_name,
            $request->customer_email,
            $order['item_name'],
            $request->currency_name
            //'237611111111'
        );

        return response()->json($response);
    }
}

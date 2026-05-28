<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\DealPage;
use Auth;
use Datatables;

class DealProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('vendor.deal_products.index');
    }

    public function datatables()
    {
        $user = Auth::user();
        $datas = Product::where('user_id', $user->id)
            ->whereNotNull('deal_page_id')
            ->orderBy('id', 'desc')
            ->get();

        return Datatables::of($datas)
            ->addColumn('deal_page', function(Product $data) {
                return $data->dealPage->name ?? 'N/A';
            })
            ->editColumn('price', function(Product $data) {
                return $data->showPrice();
            })
            ->editColumn('discount_date_end', function(Product $data) {
                return $data->discount_date_end ?? 'N/A';
            })
            ->addColumn('action', function(Product $data) {
                return '<div class="action-list"><a href="' . route('vendor-deal-product-edit',$data->id) . '" class="edit"> <i class="fas fa-edit"></i>'.__('Edit Deal').'</a><a href="javascript:;" data-href="' . route('vendor-deal-product-remove',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i> '.__('Remove Deal').'</a></div>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create()
    {
        $user = Auth::user();
        $products = Product::where('user_id', $user->id)->whereNull('deal_page_id')->get();
        $dealPages = DealPage::where('status', 1)->get();
        return view('vendor.deal_products.create', compact('products', 'dealPages'));
    }

    public function store(Request $request)
    {
        $rules = [
            'product_id' => 'required',
            'deal_page_id' => 'required',
            'deal_price' => 'required|numeric|min:0',
            'deal_end_date' => 'required|date'
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $user = Auth::user();
        $product = Product::where('user_id', $user->id)->findOrFail($request->product_id);

        // Save original price to previous_price if not already a discount
        if(empty($product->previous_price) || $product->previous_price <= 0) {
            $product->previous_price = $product->price;
        }

        $product->deal_page_id = $request->deal_page_id;
        $product->price = $request->deal_price;
        $product->discount_date_end = $request->deal_end_date;
        $product->discount_date_start = \Carbon\Carbon::now()->format('Y-m-d');
        $product->is_discount = 1;
        $product->save();

        $msg = 'Product added to deal page successfully.';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $user = Auth::user();
        $data = Product::where('user_id', $user->id)->findOrFail($id);
        $dealPages = DealPage::where('status', 1)->get();
        return view('vendor.deal_products.edit', compact('data', 'dealPages'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'deal_page_id' => 'required',
            'deal_price' => 'required|numeric|min:0',
            'deal_end_date' => 'required|date'
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $user = Auth::user();
        $product = Product::where('user_id', $user->id)->findOrFail($id);

        $product->deal_page_id = $request->deal_page_id;
        $product->price = $request->deal_price;
        $product->discount_date_end = $request->deal_end_date;
        $product->is_discount = 1;
        $product->save();

        $msg = 'Deal product updated successfully.';
        return response()->json($msg);
    }

    public function remove($id)
    {
        $user = Auth::user();
        $product = Product::where('user_id', $user->id)->findOrFail($id);

        // Revert to original price
        if($product->previous_price > 0) {
            $product->price = $product->previous_price;
        }
        
        $product->deal_page_id = null;
        $product->is_discount = 0;
        $product->discount_date_start = null;
        $product->discount_date_end = null;
        $product->save();

        $msg = 'Product removed from deal page successfully.';
        return response()->json($msg);
    }
}

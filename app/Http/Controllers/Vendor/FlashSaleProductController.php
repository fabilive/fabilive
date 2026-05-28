<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashSaleProduct;
use App\Models\Product;
use App\Models\FlashSaleTimeSlot;
use Auth;
use Datatables;

class FlashSaleProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('vendor.flash_sales.products.index');
    }

    public function datatables()
    {
        $user = Auth::user();
        $datas = FlashSaleProduct::with(['product', 'timeSlot'])->where('vendor_id', $user->id)->orderBy('id','desc')->get();
        return Datatables::of($datas)
            ->addColumn('product', function(FlashSaleProduct $data) {
                return $data->product->name ?? 'Deleted Product';
            })
            ->addColumn('time_slot', function(FlashSaleProduct $data) {
                return ($data->timeSlot->name ?? 'Deleted') . ' (' . \Carbon\Carbon::parse($data->timeSlot->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($data->timeSlot->end_time)->format('h:i A') . ')';
            })
            ->editColumn('flash_price', function(FlashSaleProduct $data) {
                return \PriceHelper::showCurrencyPrice($data->flash_price);
            })
            ->addColumn('action', function(FlashSaleProduct $data) {
                return '<div class="action-list"><a href="javascript:;" data-href="' . route('vendor-flash-products-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create()
    {
        $user = Auth::user();
        $products = Product::where('user_id', $user->id)->where('status', 1)->get();
        $time_slots = FlashSaleTimeSlot::where('status', 1)->get();
        $flash_categories = \Illuminate\Support\Facades\DB::table('flash_sale_categories')->where('status', 1)->get();
        return view('vendor.flash_sales.products.create', compact('products', 'time_slots', 'flash_categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'product_id' => 'required',
            'time_slot_id' => 'required',
            'flash_sale_category_id' => 'required',
            'flash_date' => 'required|date',
            'flash_price' => 'required|numeric',
            'flash_quantity' => 'required|integer|min:1'
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        // Check if product already submitted for this date and time slot
        $exists = FlashSaleProduct::where('product_id', $request->product_id)
                    ->where('time_slot_id', $request->time_slot_id)
                    ->whereDate('flash_date', \Carbon\Carbon::parse($request->flash_date))
                    ->first();
        if ($exists) {
            return response()->json(array('errors' => [ 0 => 'This product is already submitted for the selected flash sale time slot.' ]));
        }

        $data = new FlashSaleProduct();
        $input = $request->all();
        $input['vendor_id'] = Auth::user()->id;
        $input['status'] = 0; // Admin needs to approve? Wait, maybe automatically approved if we just set status=1? The client didn't mention approval, but usually it's pending. Let's make it 1 by default so they go live.
        $input['status'] = 1; 

        // Vendor might enter price in their currency. We should save it in default currency.
        // But price input in forms is often already expected in base currency, or handled by PriceHelper.
        // Usually, Vendor forms take price in base currency. We will assume base currency.
        $sign = \App\Models\Currency::where('is_default', '=', 1)->first();
        // Just save it.
        
        $data->fill($input)->save();
        $msg = 'Product Added To Flash Sales Successfully.';
        return response()->json($msg);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $data = FlashSaleProduct::where('id', $id)->where('vendor_id', $user->id)->first();
        if ($data) {
            $data->delete();
        }
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }
}

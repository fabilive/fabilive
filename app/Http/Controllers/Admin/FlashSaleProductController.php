<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashSaleProduct;
use Datatables;

class FlashSaleProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.flash_sales.products.index');
    }

    public function datatables()
    {
        $datas = FlashSaleProduct::with(['product', 'vendor', 'timeSlot'])->orderBy('id','desc')->get();
        return Datatables::of($datas)
            ->addColumn('product', function(FlashSaleProduct $data) {
                return $data->product->name ?? 'Deleted Product';
            })
            ->addColumn('vendor', function(FlashSaleProduct $data) {
                return $data->vendor->name ?? 'Admin';
            })
            ->addColumn('time_slot', function(FlashSaleProduct $data) {
                return ($data->timeSlot->name ?? 'Deleted') . ' (' . \Carbon\Carbon::parse($data->timeSlot->start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($data->timeSlot->end_time)->format('h:i A') . ')';
            })
            ->editColumn('flash_price', function(FlashSaleProduct $data) {
                return \PriceHelper::showAdminCurrencyPrice($data->flash_price);
            })
            ->editColumn('status', function(FlashSaleProduct $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin-flash-products-status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>'.__('Activated').'</option><option data-val="0" value="'. route('admin-flash-products-status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>'.__('Deactivated').'</option></select></div>';
            })
            ->addColumn('action', function(FlashSaleProduct $data) {
                return '<div class="action-list"><a href="javascript:;" data-href="' . route('admin-flash-products-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->rawColumns(['status','action'])
            ->toJson();
    }

    public function status($id1, $id2)
    {
        $data = FlashSaleProduct::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    public function destroy($id)
    {
        $data = FlashSaleProduct::findOrFail($id);
        $data->delete();
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FlashSaleTimeSlot;
use Datatables;

class FlashSaleTimeSlotController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.flash_sales.time_slots.index');
    }

    public function datatables()
    {
        $datas = FlashSaleTimeSlot::orderBy('id','desc')->get();
        return Datatables::of($datas)
            ->addColumn('action', function(FlashSaleTimeSlot $data) {
                return '<div class="action-list"><a href="' . route('admin-flash-time-slots-edit',$data->id) . '" class="edit"> <i class="fas fa-edit"></i>'.__('Edit').'</a><a href="javascript:;" data-href="' . route('admin-flash-time-slots-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->editColumn('status', function(FlashSaleTimeSlot $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin-flash-time-slots-status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>'.__('Activated').'</option><option data-val="0" value="'. route('admin-flash-time-slots-status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>'.__('Deactivated').'</option></select></div>';
            })
            ->rawColumns(['status','action'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.flash_sales.time_slots.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = new FlashSaleTimeSlot();
        $data->fill($request->all())->save();
        $msg = 'New Time Slot Added Successfully.';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = FlashSaleTimeSlot::findOrFail($id);
        return view('admin.flash_sales.time_slots.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required'
        ];
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = FlashSaleTimeSlot::findOrFail($id);
        $data->fill($request->all())->save();
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    public function status($id1, $id2)
    {
        $data = FlashSaleTimeSlot::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    public function destroy($id)
    {
        $data = FlashSaleTimeSlot::findOrFail($id);
        // Delete all products associated
        $data->products()->delete();
        $data->delete();
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }
}

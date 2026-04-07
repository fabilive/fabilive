<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Pickup;
use Datatables;
use Illuminate\Http\Request;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class PickupPointController extends VendorBaseController
{
    public function datatables()
    {
        try {
            $datas = Pickup::latest('id')->get();
        } catch (\Exception $e) {
            $datas = collect();
        }

        return Datatables::of($datas)
            ->addColumn('action', function (Pickup $data) {
                return '<div class="action-list">
                            <a data-href="'.route('vendor-pickup-point-edit', $data->id).'" class="edit" data-toggle="modal" data-target="#modal1"> 
                                <i class="fas fa-edit"></i>'.__('Edit').'
                            </a>
                            <a href="javascript:;" data-href="'.route('vendor-pickup-point-delete', $data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>';
            })
            ->rawColumns(['action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index()
    {
        return view('vendor.pickup.index');
    }

    public function create()
    {
        $sign = $this->curr;

        return view('vendor.pickup.create', compact('sign'));
    }

    public function store(Request $request)
    {
        $rules = ['location' => 'required|unique:pickups'];
        $customs = ['location.unique' => __('This location has already been taken.')];

        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $data = new Pickup();
        $data->location = $request->location;
        $data->save();

        $msg = __('New Data Added Successfully.');

        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = Pickup::findOrFail($id);

        return view('vendor.pickup.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $rules = ['location' => 'required|unique:pickups,location,'.$id];
        $customs = ['location.unique' => __('This location has already been taken.')];

        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $data = Pickup::findOrFail($id);
        $data->location = $request->location;
        $data->save();

        $msg = __('Data Updated Successfully.');

        return response()->json($msg);
    }

    public function destroy($id)
    {
        $data = Pickup::findOrFail($id);
        $data->delete();

        $msg = __('Data Deleted Successfully.');

        return response()->json($msg);
    }
}

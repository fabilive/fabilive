<?php

namespace App\Http\Controllers\Admin;

use App\Models\Pickup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables as Datatables;
use Yajra\DataTables\Facades\DataTables;

class PickupController extends AdminBaseController
{
    public function datatables()
    {
        $datas = Pickup::latest('id')->get();

        return Datatables::of($datas)
            ->addColumn('action', function (Pickup $data) {
                return '<div class="action-list"><a data-href="'.route('admin-pick-edit', $data->id).'" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>'.__('Edit').'</a><a href="javascript:;" data-href="'.route('admin-pick-delete', $data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index()
    {
        return view('admin.pickup.index');
    }

    public function create()
    {
        return view('admin.pickup.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'location' => 'unique:pickups',
        ];
        $customs = [
            'location.unique' => __('This location has already been taken.'),
        ];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        $data = new Pickup;
        $input = $request->all();
        $data->fill($input)->save();
        $msg = __('New Data Added Successfully.');

        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = Pickup::findOrFail($id);

        return view('admin.pickup.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'location' => 'unique:pickups,location,'.$id,
        ];
        $customs = [
            'location.unique' => __('This location has already been taken.'),
        ];
        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        $data = Pickup::findOrFail($id);
        $input = $request->all();
        $data->update($input);
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

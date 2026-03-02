<?php

namespace App\Http\Controllers\Admin;

use App\Models\ServiceArea;
use Illuminate\Http\Request;
use Validator;
use Datatables;

class ServiceAreaController extends AdminBaseController
{
    public function datatables()
    {
        $datas = ServiceArea::latest('id')->get();
        return Datatables::of($datas)
            ->addColumn('action', function(ServiceArea $data) {
                return '<div class="action-list">
                            <a data-href="' . route('admin-servicearea-edit', $data->id) . '" class="edit" data-toggle="modal" data-target="#modal1">
                                <i class="fas fa-edit"></i>' . __('Edit') . '
                            </a>
                            <a href="javascript:;" data-href="' . route('admin-servicearea-delete', $data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>';
            })
            ->toJson();
    }

    public function index()
    {
        return view('admin.servicearea.index');
    }

    public function create()
    {
        return view('admin.servicearea.create');
    }

    public function store(Request $request)
    {
        $rules = ['location' => 'unique:service_areas'];
        $customs = ['location.unique' => __('This location has already been taken.')];

        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $data = new ServiceArea;
        $input = $request->all();
        $data->fill($input)->save();

        return response()->json(__('New Data Added Successfully.'));
    }

    public function edit($id)
    {
        $data = ServiceArea::findOrFail($id);
        return view('admin.servicearea.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $rules = ['location' => 'unique:service_areas,location,' . $id];
        $customs = ['location.unique' => __('This location has already been taken.')];

        $validator = Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        $data = ServiceArea::findOrFail($id);
        $input = $request->all();
        $data->update($input);

        return response()->json(__('Data Updated Successfully.'));
    }

    public function destroy($id)
    {
        $data = ServiceArea::findOrFail($id);
        $data->delete();

        return response()->json(__('Data Deleted Successfully.'));
    }
}

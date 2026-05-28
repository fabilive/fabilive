<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DealPage;
use Illuminate\Support\Str;
use Datatables;

class DealPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('admin.deal_pages.index');
    }

    public function datatables()
    {
        $datas = DealPage::orderBy('id','desc')->get();
        return Datatables::of($datas)
            ->addColumn('action', function(DealPage $data) {
                return '<div class="action-list"><a href="' . route('admin-deal-page-edit',$data->id) . '" class="edit"> <i class="fas fa-edit"></i>'.__('Edit').'</a><a href="javascript:;" data-href="' . route('admin-deal-page-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->editColumn('status', function(DealPage $data) {
                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                $s = $data->status == 1 ? 'selected' : '';
                $ns = $data->status == 0 ? 'selected' : '';
                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin-deal-page-status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>'.__('Activated').'</option><option data-val="0" value="'. route('admin-deal-page-status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>'.__('Deactivated').'</option></select></div>';
            })
            ->rawColumns(['status','action'])
            ->toJson();
    }

    public function create()
    {
        return view('admin.deal_pages.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:deal_pages',
            'slug' => 'required|unique:deal_pages'
        ];
        $customs = [
            'name.required' => 'Name field is required.',
            'slug.required' => 'Slug field is required.',
            'name.unique' => 'This name has already been taken.',
            'slug.unique' => 'This slug has already been taken.'
        ];
        $validator = \Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = new DealPage();
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $name = \PriceHelper::ImageCreateName($file);
            $file->move('assets/images/categories', $name);
            $input['image'] = $name;
        }
        $data->fill($input)->save();
        $msg = 'New Data Added Successfully.';
        return response()->json($msg);
    }

    public function edit($id)
    {
        $data = DealPage::findOrFail($id);
        return view('admin.deal_pages.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|unique:deal_pages,name,'.$id,
            'slug' => 'required|unique:deal_pages,slug,'.$id
        ];
        $customs = [
            'name.required' => 'Name field is required.',
            'slug.required' => 'Slug field is required.',
            'name.unique' => 'This name has already been taken.',
            'slug.unique' => 'This slug has already been taken.'
        ];
        $validator = \Validator::make($request->all(), $rules, $customs);
        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }

        $data = DealPage::findOrFail($id);
        $input = $request->all();
        if ($file = $request->file('photo')) {
            $name = \PriceHelper::ImageCreateName($file);
            $file->move('assets/images/categories', $name);
            if($data->image != null) {
                if (file_exists(public_path().'/assets/images/categories/'.$data->image)) {
                    unlink(public_path().'/assets/images/categories/'.$data->image);
                }
            }
            $input['image'] = $name;
        }
        $data->update($input);
        $msg = 'Data Updated Successfully.';
        return response()->json($msg);
    }

    public function status($id1, $id2)
    {
        $data = DealPage::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    public function destroy($id)
    {
        $data = DealPage::findOrFail($id);
        // also unlink image
        if($data->image != null) {
            if (file_exists(public_path().'/assets/images/categories/'.$data->image)) {
                unlink(public_path().'/assets/images/categories/'.$data->image);
            }
        }
        $data->delete();
        $msg = 'Data Deleted Successfully.';
        return response()->json($msg);
    }
}

<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Package;
use Datatables;
use Illuminate\Http\Request;
use Validator;
use App\Helpers\PriceHelper;

class PackageController extends VendorBaseController
{
    //*** JSON Request
    public function datatables()
    {
        try {
            $datas = Package::where('user_id', $this->user->id)->get();
        } catch (\Exception $e) {
            $datas = collect();
        }

        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->editColumn('price', function (Package $data) {
                $curr = $this->curr ?? \App\Models\Currency::where('is_default', 1)->first() ?? \App\Models\Currency::where('id', '>', 0)->first();
                $val = $curr ? $curr->value : 1;
                $price = round($data->price * $val, 2);

                return \PriceHelper::showAdminCurrencyPrice($price);
            })
            ->addColumn('action', function (Package $data) {
                return '<div class="action-list"><a data-href="'.route('vendor-package-edit', $data->id).'" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>'.__('Edit').'</a><a href="javascript:;" data-href="'.route('vendor-package-delete', $data->id).'" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a></div>';
            })
            ->rawColumns(['action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index()
    {
        return view('vendor.package.index');
    }

    //*** GET Request
    public function create()
    {
        $sign = $this->curr;

        return view('vendor.package.create', compact('sign'));
    }

    //*** POST Request
    public function store(Request $request)
    {
        try {
            //--- Validation Section
            $rules = ['title' => 'unique:packages'];
            $customs = ['title.unique' => __('This title has already been taken.')];
            $validator = Validator::make($request->all(), $rules, $customs);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
            }
            //--- Validation Section Ends

            //--- Logic Section
            $sign = $this->curr;
            $data = new Package();
            $input = $request->all();
            $input['user_id'] = $this->user->id;
            $input['price'] = ($input['price'] / $sign->value);
            $data->fill($input)->save();
            $msg = __('New Data Added Successfully.');
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['errors' => [__('Could not add package info. Please try again later.')]]);
        }
    }

    //*** GET Request
    public function edit($id)
    {
        try {
            $sign = $this->curr;
            $data = Package::findOrFail($id);
            return view('vendor.package.edit', compact('data', 'sign'));
        } catch (\Exception $e) {
            return back()->with('error', __('Package info not found.'));
        }
    }

    //*** POST Request
    public function update(Request $request, $id)
    {
        try {
            //--- Validation Section
            $rules = ['title' => 'unique:packages,title,'.$id];
            $customs = ['title.unique' => __('This title has already been taken.')];
            $validator = Validator::make($request->all(), $rules, $customs);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
            }
            //--- Validation Section Ends

            //--- Logic Section
            $sign = $this->curr;
            $data = Package::findOrFail($id);
            $input = $request->all();
            $input['price'] = ($input['price'] / $sign->value);
            $data->update($input);
            $msg = __('Data Updated Successfully.');
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['errors' => [__('Could not update package info. Please try again later.')]]);
        }
    }

    //*** GET Request Delete
    public function destroy($id)
    {
        $data = Package::findOrFail($id);
        $data->delete();
        //--- Redirect Section
        $msg = __('Data Deleted Successfully.');

        return response()->json($msg);
        //--- Redirect Section Ends
    }
}

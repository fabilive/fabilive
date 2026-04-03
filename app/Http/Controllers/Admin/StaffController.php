<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Auth;
use Validator;
use Datatables;
use Hash;

class StaffController extends AdminBaseController
{
    //*** JSON Request
    public function datatables()
    {
         $datas = Admin::where('id','!=',1)->where('id','!=',Auth::guard('admin')->user()->id)->latest('id')->get();
         //--- Integrating This Collection Into Datatables
         return Datatables::of($datas)
                            ->addColumn('role', function(Admin $data) {
                                if($data->role_id == 0) {
                                    return $data->section ? __('Custom Permissions') : __('No Role');
                                } else {
                                    return $data->role->name;
                                }
                            }) 
                            ->addColumn('action', function(Admin $data) {
                                $delete ='<a href="javascript:;" data-href="' . route('admin-staff-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>';
                                return '<div class="action-list"><a data-href="' . route('admin-staff-show',$data->id) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i>'.__("Details").'</a><a data-href="' . route('admin-staff-edit',$data->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>'.__('Edit').'</a>'.$delete.'</div>';
                            }) 
                            ->rawColumns(['action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index(){
        return view('admin.staff.index');
    }

    public function create()
    {
        try {
            $roles = Role::get();
            return view('admin.staff.create', compact('roles'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
               'email'      => 'required|email|unique:admins',
               'photo'      => 'required|mimes:jpeg,jpg,png,svg',
                ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = new Admin();
        $input = $request->all();
        if ($file = $request->file('photo')) 
         {      
            $name = \PriceHelper::ImageCreateName($file);
            $file->move('assets/images/admins',$name);           
            $input['photo'] = $name;
        } 
        $input['role'] = 'Staff';
        $input['password'] = bcrypt($request['password']);
        
        if (isset($input['section'])) {
            $input['section'] = implode(" , ", $input['section']);
        } else {
            $input['section'] = '';
        }
        
        $data->fill($input)->save();
        //--- Logic Section Ends

        //--- Redirect Section        
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends    
    }


    public function edit($id)
    {
        try {
            $roles = Role::get();
            $data = Admin::findOrFail($id);
            return view('admin.staff.edit', compact('data', 'roles'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request,$id)
    {
        //--- Validation Section
        if($id != Auth::guard('admin')->user()->id)
        {
            $rules =
            [
                'photo' => 'mimes:jpeg,jpg,png,svg',
                'email' => 'required|unique:admins,email,'.$id
            ];

            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
            }
            //--- Validation Section Ends
            $input = $request->all();  
            $data = Admin::findOrFail($id);        
                if ($file = $request->file('photo')) 
                {              
                    $name = \PriceHelper::ImageCreateName($file);
                    $file->move('assets/images/admins/',$name);
                    if($data->photo != null)
                    {
                        if (file_exists(public_path().'/assets/images/admins/'.$data->photo)) {
                            unlink(public_path().'/assets/images/admins/'.$data->photo);
                        }
                    }            
                $input['photo'] = $name;
                } 
            if($request->password == ''){
                $input['password'] = $data->password;
            }
            else{
                $input['password'] = Hash::make($request->password);
            }
            if (isset($input['section'])) {
                $input['section'] = implode(" , ", $input['section']);
            } else {
                $input['section'] = '';
            }

            $data->update($input);
            $msg = __('Data Updated Successfully.');
            return response()->json($msg);
        }
        else{
            $msg = __('You can not change your role.');
            return response()->json($msg);            
        }
 
    }

    //*** GET Request
    public function show($id)
    {
        $data = Admin::findOrFail($id);
        return view('admin.staff.show',compact('data'));
    }

    //*** GET Request Delete
    public function destroy($id)
    {
    	if($id == 1)
    	{
        return "You don't have access to remove this admin";
    	}
        $data = Admin::findOrFail($id);
        //If Photo Doesn't Exist
        if($data->photo == null){
            $data->delete();
            //--- Redirect Section     
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);      
            //--- Redirect Section Ends     
        }
        //If Photo Exist
        if (file_exists(public_path().'/assets/images/admins/'.$data->photo)) {
            unlink(public_path().'/assets/images/admins/'.$data->photo);
        }
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends    
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Models\Verification;

use Illuminate\Http\Request;
use Datatables;

class VerificationController extends AdminBaseController
{
    //*** JSON Request
    public function datatables($status)
    {
        if($status == 'Pending'){
            $datas = Verification::where('status','=','Pending')->get();
        }
        else{
           $datas = Verification::get();
        }
         
         return Datatables::of($datas)
                            ->addColumn('name', function(Verification $data) {
                                return $data->user->name ?? $data->user->owner_name ?? __('Removed');
                            })
                            ->addColumn('email', function(Verification $data) {
                                return $data->user->email ?? __('Removed');
                            })
                            ->editColumn('text', function(Verification $data) {
                                $details = mb_strlen($data->text,'UTF-8') > 250 ? mb_substr($data->text,0,250,'UTF-8').'...' : $data->text;
                                return  $details;
                            })
                            ->addColumn('status', function(Verification $data) {
                                $class = $data->status == 'Pending' ? '' : ($data->status == 'Verified' ? 'drop-success' : 'drop-danger');
                                $s = $data->status == 'Verified' ? 'selected' : '';
                                $ns = $data->status == 'Declined' ? 'selected' : '';
                                return '<div class="action-list"><select class="process select vendor-droplinks '.$class.'">'.
                                 '<option value="'. route('admin-vr-st',['id1' => $data->id, 'id2' => 'Pending']).'" '.$s.'>'.__("Pending").'</option>'.
                                '<option value="'. route('admin-vr-st',['id1' => $data->id, 'id2' => 'Verified']).'" '.$s.'>'.__("Verified").'</option>'.
                                '<option value="'. route('admin-vr-st',['id1' => $data->id, 'id2' => 'Declined']).'" '.$ns.'>'.__("Declined").'</option></select></div>';
                            }) 
                            ->addColumn('attachments', function(Verification $data) {
                                if($data->attachments) {
                                    return '<img src="'.asset('assets/images/attachments/'.$data->attachments).'" style="height: 50px; width: 50px;">';
                                }
                                return __('No Attachment');
                            })
                            ->addColumn('action', function(Verification $data) {
                                $user = $data->user;
                                if(!$user->id) return '';
                                
                                return '<div class="action-list">
                                            <a href="javascript:;" class="set-gallery" data-toggle="modal" data-target="#setgallery">
                                                <input type="hidden" value="'.$data->id.'">
                                                <i class="fas fa-paperclip"></i> '.__('View Attachments').
                                            '</a>
                                            <div class="godropdown d-inline-block ml-2">
                                                <button class="go-dropdown-toggle"> ' . __("Actions") . '<i class="fas fa-chevron-down"></i></button>
                                                <div class="action-list">
                                                    <a href="' . route('admin-vendor-secret', $user->id) . '" > <i class="fas fa-user"></i> ' . __("Secret Login") . '</a>
                                                    <a href="javascript:;" data-href="' . route('admin-vendor-add-subs', $user->id) . '" class="add-subs" data-toggle="modal" data-target="#ad-subscription-modal"> <i class="fas fa-plus"></i> ' . __("Add New Plan") . '</a>
                                                    <a href="javascript:;" data-href="' . route('admin-vendor-verify', $user->id) . '" class="verify" data-toggle="modal" data-target="#verify-modal"> <i class="fas fa-question"></i> ' . __("Ask For Verification") . '</a>
                                                    <a href="' . route('admin-vendor-show', $user->id) . '" > <i class="fas fa-eye"></i> ' . __("Details") . '</a>
                                                    <a data-href="' . route('admin-vendor-edit', $user->id) . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i> ' . __("Edit") . '</a>
                                                    <a href="javascript:;" class="send" data-email="' . $user->email . '" data-toggle="modal" data-target="#vendorform"><i class="fas fa-envelope"></i> ' . __("Send Email") . '</a>
                                                    <a href="javascript:;" data-href="' . route('admin-vr-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>';
                            }) 
                            ->rawColumns(['status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function verificatons($slug)
    {
        if($slug == 'all'){
            return view('admin.verify.index');
        }else if($slug == 'pending'){
            return view('admin.verify.pending');
        }

    }

    public function show()
    {
        $data[0] = 0;
        $id = $_GET['id'];
        $prod1 = Verification::findOrFail($id);
        $prod = explode(',', $prod1->attachments);
        if(count($prod))
        {
            $data[0] = 1;
            $data[1] = $prod;
            $data[2] = $prod1->text;
            $data[3] = ''.route('admin-vr-st',['id1' => $prod1->id, 'id2' => 'Verified']).'';
            $data[4] = ''.route('admin-vr-st',['id1' => $prod1->id, 'id2' => 'Declined']).'';
        }
        return response()->json($data);              
    }  


    public function edit($id)
    {
        $data = Order::find($id);
        return view('admin.order.delivery',compact('data'));
    }


    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Logic Section
        $data = Order::findOrFail($id);

        $input = $request->all();


        // Then Save Without Changing it.
            $input['status'] = "completed";
            $data->update($input);
            //--- Logic Section Ends
    
        //--- Redirect Section          
        $msg = __('Status Updated Successfully.');
        return response()->json($msg);    
        //--- Redirect Section Ends     

    }


    //*** GET Request
    public function status($id1,$id2)
    {
        $user = Verification::findOrFail($id1);
        $user->status = $id2;
        $user->update();
        //--- Redirect Section        
        $msg[0] = __('Status Updated Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends    

    }

    //*** GET Request
    public function destroy($id)
    {
        $data = Verification::findOrFail($id);
        $photos =  explode(',',$data->attachments);
        foreach($photos as $photo){
            unlink(public_path().'/assets/images/attachments/'.$photo);
        }
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends    

    }

}
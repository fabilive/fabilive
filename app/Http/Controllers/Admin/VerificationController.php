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
                                $user = $data->user;
                                if (!$user || !$user->id) return __('Removed');
                                return $user->name ?? $user->owner_name ?? __('Removed');
                            })
                            ->addColumn('email', function(Verification $data) {
                                $user = $data->user;
                                if (!$user || !$user->id) return __('Removed');
                                return $user->email ?? __('Removed');
                            })
                            ->editColumn('text', function(Verification $data) {
                                $details = mb_strlen($data->text,'UTF-8') > 250 ? mb_substr($data->text,0,250,'UTF-8').'...' : $data->text;
                                return  $details;
                            })
                            ->addColumn('status', function(Verification $data) {
                                $class = $data->status == 'Pending' ? '' : ($data->status == 'Verified' ? 'drop-success' : 'drop-danger');
                                $ps = $data->status == 'Pending' ? 'selected' : '';
                                $s = $data->status == 'Verified' ? 'selected' : '';
                                $ns = $data->status == 'Declined' ? 'selected' : '';
                                return '<div class="action-list"><select class="process select vendor-droplinks '.$class.'">'.
                                 '<option value="'. route('admin-vr-st',['id1' => $data->id, 'id2' => 'Pending']).'" '.$ps.'>'.__('Pending').'</option>'.
                                '<option value="'. route('admin-vr-st',['id1' => $data->id, 'id2' => 'Verified']).'" '.$s.'>'.__('Verified').'</option>'.
                                '<option value="'. route('admin-vr-st',['id1' => $data->id, 'id2' => 'Declined']).'" '.$ns.'>'.__('Declined').'</option></select></div>';
                            }) 
                            ->addColumn('attachments', function(Verification $data) {
                                if($data->attachments) {
                                    $firstAttachment = explode(',', $data->attachments)[0];
                                    return '<img src="'.asset('assets/images/attachments/'.trim($firstAttachment)).'" style="height: 50px; width: 50px;">';
                                }
                                return __('No Attachment');
                            })
                            ->addColumn('action', function(Verification $data) {
                                $user = $data->user;
                                if(!$user || !$user->id) {
                                    return '<div class="action-list">
                                                <span class="text-muted">'.__('Vendor Removed').'</span>
                                                <a href="javascript:;" data-href="' . route('admin-vr-delete',$data->id) . '" data-toggle="modal" data-target="#confirm-delete" class="delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>';
                                }
                                
                                return '<div class="action-list">
                                            <a href="javascript:;" class="set-gallery" data-toggle="modal" data-target="#setgallery">
                                                <input type="hidden" value="'.$data->id.'">
                                                <i class="fas fa-paperclip"></i> '.__('View Attachments').'
                                            </a>
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
                            ->rawColumns(['status','action','attachments'])
                            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function verificatons($slug)
    {
        try {
            if($slug == 'all'){
                return response(view('admin.verify.index')->render());
            }else if($slug == 'pending'){
                return response(view('admin.verify.pending')->render());
            }
            return redirect()->route('admin-vr-index', 'all');
        } catch (\Throwable $e) {
            return back()->with('unsuccess', 'Runtime Exception: ' . $e->getMessage());
        }
    }

    public function show(Request $request)
    {
        $data[0] = 0;
        $id = $request->input('id');
        if (!$id) {
            return response()->json($data);
        }
        $prod1 = Verification::find($id);
        if (!$prod1) {
            return response()->json($data);
        }
        $prod = explode(',', $prod1->attachments);
        if(count($prod) && !empty(trim($prod[0])))
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
        $data = Verification::findOrFail($id);
        return view('admin.verify.index', compact('data'));
    }


    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Logic Section
        $data = Verification::findOrFail($id);

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
        try {
            $user = Verification::findOrFail($id1);
            $user->status = $id2;
            $user->update();
            //--- Redirect Section        
            $msg[0] = __('Status Updated Successfully.');
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json([__('Error updating status.')], 500);
        }
        //--- Redirect Section Ends    

    }

    //*** GET Request
    public function destroy($id)
    {
        try {
            $data = Verification::findOrFail($id);
            if ($data->attachments) {
                $photos = explode(',', $data->attachments);
                foreach ($photos as $photo) {
                    $photo = trim($photo);
                    if ($photo && file_exists(public_path() . '/assets/images/attachments/' . $photo)) {
                        @unlink(public_path() . '/assets/images/attachments/' . $photo);
                    }
                }
            }
            $data->delete();
            $msg = __('Data Deleted Successfully.');
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Delete failed: ' . $e->getMessage()], 500);
        }
    }

}
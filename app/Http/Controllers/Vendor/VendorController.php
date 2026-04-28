<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Category;
use App\Models\Generalsetting;
use App\Models\LiveMessage;
use App\Models\Product;
use App\Models\Subcategory;
use App\Models\User;
use App\Models\VendorOrder;
use App\Models\Verification;
use Illuminate\Http\Request;
use Validator;
use App\Helpers\PriceHelper;

class VendorController extends VendorBaseController
{
    public function index()
    {
        $vendorId = $this->user->id;
        $data['days'] = '';
        $data['sales'] = '';
        
        // Last 30 days sales chart
        for ($i = 0; $i < 30; $i++) {
            $date = date('Y-m-d', strtotime('-'.$i.' days'));
            $data['days'] .= "'".date('d M', strtotime('-'.$i.' days'))."',";
            try {
                $count = VendorOrder::where('user_id', $vendorId)
                    ->where('status', 'completed')
                    ->whereDate('created_at', $date)
                    ->count();
                $data['sales'] .= "'".$count."',";
            } catch (\Exception $e) {
                $data['sales'] .= "'0',";
            }
        }
        
        $data['user'] = $this->user;
        $data['pproducts'] = Product::where('user_id', $vendorId)->latest('id')->take(6)->get();
        $data['rorders'] = VendorOrder::where('user_id', $vendorId)->latest('id')->take(10)->get();
        
        // Status counts
        $data['pending'] = VendorOrder::where('user_id', $vendorId)->where('status', 'pending')->get();
        $data['processing'] = VendorOrder::where('user_id', $vendorId)->where('status', 'processing')->get();
        $data['completed'] = VendorOrder::where('user_id', $vendorId)->whereIn('status', ['completed', 'delivered'])->get();
        
        // Financials
        $data['actual_balance'] = $this->user->current_balance; // Settled funds
        $data['pending_balance'] = VendorOrder::where('user_id', $vendorId)
            ->whereIn('status', ['processing', 'delivered'])
            ->sum('price');
        $data['total_earning'] = VendorOrder::where('user_id', $vendorId)
            ->whereIn('status', ['completed', 'delivered'])
            ->sum('price');




        return view('vendor.index', $data);
    }


    public function profileupdate(Request $request)
    {
        //--- Validation Section
        $rules = [
            'shop_image' => 'mimes:jpeg,jpg,png,svg',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        //--- Validation Section Ends

        $input = $request->all();
        $data = $this->user;

        if ($file = $request->file('shop_image')) {
            $extensions = ['jpeg', 'jpg', 'png', 'svg'];
            if (! in_array($file->getClientOriginalExtension(), $extensions)) {
                return response()->json(['errors' => ['Image format not supported']]);
            }
            $name = \PriceHelper::ImageCreateName($file);
            $file->move('assets/images/vendorbanner', $name);
            $input['shop_image'] = $name;
        }

        try {
            $data->update($input);
            $msg = __('Successfully updated your profile');
        } catch (\Exception $e) {
            return response()->json(['errors' => [__('Could not update profile. Please try again later.')]]);
        }

        return response()->json($msg);
    }

    // Spcial Settings All post requests will be done in this method
    public function socialupdate(Request $request)
    {
        //--- Logic Section
        $input = $request->all();
        $data = $this->user;
        if ($request->f_check == '') {
            $input['f_check'] = 0;
        }
        if ($request->t_check == '') {
            $input['t_check'] = 0;
        }

        if ($request->g_check == '') {
            $input['g_check'] = 0;
        }

        if ($request->l_check == '') {
            $input['l_check'] = 0;
        }
        try {
            $data->update($input);
            $msg = __('Data Updated Successfully.');
        } catch (\Exception $e) {
            return response()->json(['errors' => [__('Could not update social links. Please try again later.')]]);
        }

        return response()->json($msg);
        //--- Redirect Section Ends

    }

    //*** GET Request
    public function profile()
    {
        $data = $this->user;

        return view('vendor.profile', compact('data'));
    }

    //*** GET Request
    public function ship()
    {
        if ($this->gs->vendor_ship_info == 0) {
            return redirect()->back();
        }
        $data = $this->user;

        return view('vendor.ship', compact('data'));
    }

    //*** GET Request
    public function banner()
    {
        $data = $this->user;

        return view('vendor.banner', compact('data'));
    }

    //*** GET Request
    public function social()
    {
        $data = $this->user;

        return view('vendor.social', compact('data'));
    }

    //*** GET Request
    public function subcatload($id)
    {
        try {
            $cat = Category::findOrFail($id);
            return view('load.subcategory', compact('cat'));
        } catch (\Exception $e) {
            return view('load.subcategory', ['cat' => null]);
        }
    }

    //*** GET Request
    public function childcatload($id)
    {
        try {
            $subcat = Subcategory::findOrFail($id);
            return view('load.childcategory', compact('subcat'));
        } catch (\Exception $e) {
            return view('load.childcategory', ['subcat' => null]);
        }
    }

    //*** GET Request
    public function verify()
    {
        $data = $this->user;
        if ($data->checkStatus()) {
            return redirect()->back();
        }

        return view('vendor.verify', compact('data'));
    }

    //*** GET Request
    public function warningVerify($id)
    {
        try {
            $verify = Verification::findOrFail($id);
            $data = $this->user;
            return view('vendor.verify', compact('data', 'verify'));
        } catch (\Exception $e) {
            return back()->with('error', __('Verification data not found.'));
        }
    }

    //*** POST Request
    public function verifysubmit(Request $request)
    {
        //--- Validation Section
        $rules = [
            'attachments.*' => 'mimes:jpeg,jpg,png,svg|max:10000',
        ];
        $customs = [
            'attachments.*.mimes' => __('Only jpeg, jpg, png and svg images are allowed'),
            'attachments.*.max' => __('Sorry! Maximum allowed size for an image is 10MB'),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        //--- Validation Section Ends

        $data = new Verification();
        $input = $request->all();

        $input['attachments'] = '';
        $i = 0;
        if ($files = $request->file('attachments')) {
            foreach ($files as $key => $file) {
                $name = \PriceHelper::ImageCreateName($file);
                if ($i == count($files) - 1) {
                    $input['attachments'] .= $name;
                } else {
                    $input['attachments'] .= $name.',';
                }
                $file->move('assets/images/attachments', $name);

                $i++;
            }
        }
        $input['status'] = 'Pending';
        $input['user_id'] = $this->user->id;
        try {
            if ($request->verify_id != '0') {
                $verify = Verification::findOrFail($request->verify_id);
                $input['admin_warning'] = 0;
                $verify->update($input);
            } else {
                $data->fill($input)->save();
            }
            $msg = '<div class="text-center"><i class="fas fa-check-circle fa-4x"></i><br><h3>'.__('Your Documents Submitted Successfully.').'</h3></div>';
            return response()->json($msg);
        } catch (\Exception $e) {
            return response()->json(['errors' => [__('Documents submission failed. Please try again.')]]);
        }
        //--- Redirect Section Ends
    }

    public function sellerMessages()
    {
        try {
            $data = $this->user;
            $sellerId = $data->id;

            $customers = User::whereIn('id', function ($query) use ($sellerId) {
                $query->select('sender_id')
                    ->from('live_messages')
                    ->where('receiver_id', $sellerId);
            })->get();

            return view('vendor.messages', compact('customers'));
        } catch (\Exception $e) {
            return view('vendor.messages', ['customers' => collect()]);
        }
    }

    public function sellerChat($customerId)
    {
        $sellerId = auth()->id();

        // Get messages between the seller and customer
        $messages = LiveMessage::where(function ($query) use ($sellerId, $customerId) {
            $query->where('sender_id', $sellerId)->where('receiver_id', $customerId);
        })->orWhere(function ($query) use ($sellerId, $customerId) {
            $query->where('sender_id', $customerId)->where('receiver_id', $sellerId);
        })->orderBy('created_at', 'asc')->get();

        // Mark messages as read
        LiveMessage::where('sender_id', $customerId)
            ->where('receiver_id', $sellerId)
            ->update(['is_read' => true]);

        return view('vendor.chat', compact('messages', 'customerId'));
    }
}

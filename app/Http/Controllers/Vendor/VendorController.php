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
        $data['days'] = '';
        $data['sales'] = '';
        for ($i = 0; $i < 30; $i++) {
            $data['days'] .= "'".date('d M', strtotime('-'.$i.' days'))."',";
            try {
                $count = VendorOrder::where('user_id', '=', $this->user->id)->where('status', '=', 'completed')->whereDate('created_at', '=', date('Y-m-d', strtotime('-'.$i.' days')))->count();
                $data['sales'] .= "'".$count."',";
            } catch (\Exception $e) {
                $data['sales'] .= "'0',";
            }
        }
        
        $data['pproducts'] = collect();
        $data['rorders'] = collect();
        $data['pending'] = collect();
        $data['processing'] = collect();
        $data['completed'] = collect();
        
        try {
            $data['pproducts'] = Product::where('user_id', '=', $this->user->id)->latest('id')->take(6)->get();
            $data['rorders'] = VendorOrder::where('user_id', '=', $this->user->id)->latest('id')->take(10)->get();
            $data['pending'] = VendorOrder::where('user_id', '=', $this->user->id)->where('status', '=', 'pending')->get();
            $data['processing'] = VendorOrder::where('user_id', '=', $this->user->id)->where('status', '=', 'processing')->get();
            $data['completed'] = VendorOrder::where('user_id', '=', $this->user->id)->where('status', '=', 'completed')->get();
        } catch (\Exception $e) {}

        $data['user'] = $this->user;
        $actualBalance = $this->user ? $this->user->current_balance : 0;
        $data['actual_balance'] = $actualBalance;

        return view('vendor.index', $data);
    }

    //     public function index()
    //     {
    //     $vendorId = $this->user->id;
    //     $data['days'] = "";
    //     $data['sales'] = "";
    //     for ($i = 0; $i < 30; $i++) {
    //         $data['days'] .= "'" . date("d M", strtotime('-' . $i . ' days')) . "',";
    //         $data['sales'] .= "'" . VendorOrder::where('user_id', $vendorId)
    //             ->where('status', 'completed')
    //             ->whereDate('created_at', date("Y-m-d", strtotime('-' . $i . ' days')))
    //             ->count() . "',";
    //     }
    //     $commissionTotal = VendorOrder::where('user_id', $vendorId)
    //         ->where('status', 'completed')
    //         ->with('order') // eager load to avoid N+1 problem
    //         ->get()
    //         ->sum(function ($vo) {
    //             return $vo->order ? $vo->order->commission : 0;
    //         });
    //     $actualBalance =  $this->user->current_balance - $commissionTotal;
    //     $data['pproducts'] = Product::where('user_id', $vendorId)->latest('id')->take(6)->get();
    //     $data['rorders'] = VendorOrder::where('user_id', $vendorId)->latest('id')->take(10)->get();
    //     $data['user'] = $this->user;
    //     $data['pending'] = VendorOrder::where('user_id', $vendorId)->where('status', 'pending')->get();
    //     $data['processing'] = VendorOrder::where('user_id', $vendorId)->where('status', 'processing')->get();
    //     $data['completed'] = VendorOrder::where('user_id', $vendorId)->where('status', 'completed')->get();
    //     $data['actual_balance'] = $actualBalance;
    //     return view('vendor.index', $data);
    // }

    //         public function index()
    //         {
    //     $vendorId = $this->user->id;

    //     $data['days'] = "";
    //     $data['sales'] = "";

    //     for ($i = 0; $i < 30; $i++) {
    //         $data['days'] .= "'" . date("d M", strtotime('-' . $i . ' days')) . "',";
    //         $data['sales'] .= "'" . \App\Models\VendorOrder::where('user_id', $vendorId)
    //             ->where('status', 'completed')
    //             ->whereDate('created_at', date("Y-m-d", strtotime('-' . $i . ' days')))
    //             ->count() . "',";
    //     }

    //     // ✅ Correct commission calculation from related orders
    //     $commissionTotal = \App\Models\VendorOrder::where('user_id', $vendorId)
    //         ->whereHas('order') // only if order exists
    //         ->with('order')     // eager load to prevent N+1 queries
    //         ->get()
    //         ->sum(function ($vendorOrder) {
    //             return $vendorOrder->order->commission ?? 0;
    //         });

    //     // ✅ Subtract commission from current_balance in users table
    //     $actualBalance = $this->user->current_balance - $commissionTotal;

    //     // ✅ Pass data to view
    //     $data['pproducts'] = \App\Models\Product::where('user_id', $vendorId)->latest('id')->take(6)->get();
    //     $data['rorders'] = \App\Models\VendorOrder::where('user_id', $vendorId)->latest('id')->take(10)->get();
    //     $data['user'] = $this->user;
    //     $data['pending'] = \App\Models\VendorOrder::where('user_id', $vendorId)->where('status', 'pending')->get();
    //     $data['processing'] = \App\Models\VendorOrder::where('user_id', $vendorId)->where('status', 'processing')->get();
    //     $data['completed'] = \App\Models\VendorOrder::where('user_id', $vendorId)->where('status', 'completed')->get();
    //     $data['actual_balance'] = $actualBalance;

    //     return view('vendor.index', $data);
    // }

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

        $data->update($input);
        $msg = __('Successfully updated your profile');

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
        $data->update($input);
        //--- Logic Section Ends
        //--- Redirect Section
        $msg = __('Data Updated Successfully.');

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
        $cat = Category::findOrFail($id);

        return view('load.subcategory', compact('cat'));
    }

    //*** GET Request
    public function childcatload($id)
    {
        $subcat = Subcategory::findOrFail($id);

        return view('load.childcategory', compact('subcat'));
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
        $verify = Verification::findOrFail($id);
        $data = $this->user;

        return view('vendor.verify', compact('data', 'verify'));
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
        if ($request->verify_id != '0') {
            $verify = Verification::findOrFail($request->verify_id);
            $input['admin_warning'] = 0;
            $verify->update($input);
        } else {

            $data->fill($input)->save();
        }

        //--- Redirect Section
        $msg = '<div class="text-center"><i class="fas fa-check-circle fa-4x"></i><br><h3>'.__('Your Documents Submitted Successfully.').'</h3></div>';

        return response()->json($msg);
        //--- Redirect Section Ends
    }

    public function sellerMessages()
    {
        $data = $this->user;
        $sellerId = $data->id;

        $customers = User::whereIn('id', function ($query) use ($sellerId) {
            $query->select('sender_id')
                ->from('live_messages')
                ->where('receiver_id', $sellerId);
        })->get();

        return view('vendor.messages', compact('customers'));
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

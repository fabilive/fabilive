<?php
namespace App\Http\Controllers\Rider;

use App\Models\Chat;
use App\Models\ServiceArea;
use App\Models\City;
use App\Models\Currency;
use App\Models\DeliveryRider;
use App\Models\FavoriteSeller;
use App\Models\Order;
use App\Models\PaymentGateway;
use App\Models\RiderServiceArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Models\DeliveryJob;
use App\Services\DeliveryAcceptanceService;
use App\Services\DeliveryJobService;
class RiderController extends RiderBaseController
{
    public function index()
    {
        $user = $this->rider;
        $orders = DeliveryRider::where('rider_id', $this->rider->id)
            ->orderby('id','desc')->take(8)->get();

        // Fetch available jobs in rider's service area
        $available_jobs = \App\Models\DeliveryJob::where('status', 'available')
            ->whereIn('service_area_id', $user->serviceAreas->pluck('id'))
            ->with(['order', 'stops'])
            ->latest()
            ->take(5)
            ->get();

        return view('rider.dashboard', compact('orders', 'user', 'available_jobs'));
    }

    public function profile()
    {
        $user = $this->rider;
        return view('rider.profile', compact('user'));
    }

    public function profileupdate(Request $request)
    {
        $rules =
            [
                'name' => 'required|max:255',
                'phone' => 'required|max:255',
                'photo' => 'mimes:jpeg,jpg,png,svg',
                'email' => 'unique:riders,email,' . $this->rider->id,
            ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends
        $input = $request->all();
        $data = $this->rider;
        if ($file = $request->file('photo')) {
            $extensions = ['jpeg', 'jpg', 'png', 'svg'];
            if (!in_array($file->getClientOriginalExtension(), $extensions)) {
                return response()->json(array('errors' => ['Image format not supported']));
            }

            $name = \PriceHelper::ImageCreateName($file);
            $file->move('assets/images/users/', $name);
            if ($data->photo != null) {
                if (file_exists(public_path() . '/assets/images/users/' . $data->photo)) {
                    unlink(public_path() . '/assets/images/users/' . $data->photo);
                }
            }
            $input['photo'] = $name;
        }
        $data->update($input);
        $msg = __('Successfully updated your profile');
        return response()->json($msg);
    }

    public function resetform()
    {
        return view('rider.reset');
    }

    public function reset(Request $request)
    {
        $user = $this->rider;
        if ($request->cpass) {
            if (Hash::check($request->cpass, $user->password)) {
                if ($request->newpass == $request->renewpass) {
                    $input['password'] = Hash::make($request->newpass);
                } else {
                    return response()->json(array('errors' => [0 => __('Confirm password does not match.')]));
                }
            } else {
                return response()->json(array('errors' => [0 => __('Current password Does not match.')]));
            }
        }
        $user->update($input);
        $msg = __('Successfully changed your password');
        return response()->json($msg);
    }
    public function serviceArea()
    {
        $rider = $this->rider;
        $service_area = RiderServiceArea::where('rider_id', $rider->id)->with('serviceArea')->get();
        $alreadySelected = $service_area->count() > 0;
        return view('rider.service-area', compact('service_area', 'alreadySelected'));
    }
    // public function serviceAreaEdit($id)
    // {
    //     $cities = City::all();
    //     $service_area = RiderServiceArea::findOrFail($id);
    //     return view('rider.edit_service', compact('cities', 'service_area'));
    // }

    public function serviceAreaEdit($id)
{
    $service_areas = ServiceArea::all();
    $service_area = RiderServiceArea::findOrFail($id);
    return view('rider.edit_service', compact('service_areas', 'service_area'));
}


    // public function serviceAreaCreate()
    // {
    //     $cities = City::all();
    //     return view('rider.add_service', compact('cities'));
    // }

    public function serviceAreaCreate()
{
    $service_areas = ServiceArea::all();
    return view('rider.add_service', compact('service_areas'));
}


//     public function serviceAreaStore(Request $request)
// {
//     $rules = [
//         'service_area_id' =>
//             'required|exists:cities,id|unique:rider_service_areas,city_id,NULL,id,rider_id,' . $this->rider->id,
//     ];

//     $validator = Validator::make($request->all(), $rules);

//     if ($validator->fails()) {
//         return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
//     }
//     $service_area = new RiderServiceArea();
//     $service_area->rider_id = $this->rider->id;
//     $service_area->city_id = $request->service_area_id;
//     $service_area->save();

//     return response()->json(__('Successfully created your service area'));
// }

    public function serviceAreaStore(Request $request)
{
    $rules = [
        'service_area_id' =>
            'required|exists:service_areas,id|unique:rider_service_areas,service_area_id,NULL,id,rider_id,' . $this->rider->id,
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
    }

    $service_area = new RiderServiceArea();
    $service_area->rider_id = $this->rider->id;
    $service_area->service_area_id = $request->service_area_id;
    $service_area->save();

    return response()->json(__('Successfully created your service area'));
}

    public function serviceAreaUpdate(Request $request, $id)
{
    $rules = [
        'service_area_id' =>
            'required|exists:service_areas,id|unique:rider_service_areas,service_area_id,' . $id . ',id,rider_id,' . $this->rider->id,
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
    }

    $service_area = RiderServiceArea::findOrFail($id);
    $service_area->rider_id = $this->rider->id;
    $service_area->service_area_id = $request->service_area_id;

    if ($request->filled('price')) {
        $service_area->price = $request->price / $this->curr->value;
    }

    $service_area->save();
    return response()->json(__('Successfully updated your service area'));
}


//     public function serviceAreaUpdate(Request $request, $id)
// {
//     $rules = [
//         'service_area_id' => 'required|exists:cities,id|unique:rider_service_areas,city_id,' . $id . ',id,rider_id,' . $this->rider->id,
//     ];
//     $validator = Validator::make($request->all(), $rules);
//     if ($validator->fails()) {
//         return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
//     }
//     $service_area = RiderServiceArea::findOrFail($id);
//     $service_area->rider_id = $this->rider->id;
//     $service_area->city_id = $request->service_area_id;
//     if ($request->filled('price')) {
//         $service_area->price = $request->price / $this->curr->value;
//     }
//     $service_area->save();
//     return response()->json(__('Successfully updated your service area'));
// }

    public function serviceAreaDestroy($id)
    {
        $service_area = RiderServiceArea::where('rider_id', $this->rider->id)->where('id', $id)->first();
        $service_area->delete();
        $msg = __('Successfully deleted your service area');
        return back()->with('success', $msg);
    }

    public function orders(Request $request)
    {
        if($request->type == 'complete'){
            $orders = DeliveryRider::where('rider_id', $this->rider->id)
            ->where('status', 'delivered')->orderby('id','desc')->get();
            return view('rider.orders', compact('orders'));
        }else{
            $orders = DeliveryRider::where('rider_id', $this->rider->id)->with(['order:id,pickup_location,total_delivery_fee,order_number'])
            ->where('status', '!=', 'delivered')->orderby('id','desc')->get();
            // dd($orders);
            return view('rider.orders', compact('orders'));
        }
    }

    // public function orderDetails($id)
    // {
    //     $data = DeliveryRider::with('order')->where('rider_id', $this->rider->id)->where('id', $id)->first();
    //     return view('rider.order_details', compact('data'));
    // }

    public function orderDetails($id)
{
    $data = DeliveryRider::with(['order', 'vendor', 'pickup' , 'serviceAreas'])
                ->where('rider_id', $this->rider->id)
                ->where('id', $id)
                ->first();

                // dd($data);

    if (!$data) {
        abort(404, 'Delivery not found'); // safe fallback
    }

    return view('rider.order_details', compact('data'));
}


public function orderAccept($id)
{
    // 1️⃣ Find the delivery assignment for this rider
    $delivery = DeliveryRider::where('rider_id', $this->rider->id)
                              ->where('id', $id)
                              ->first();

    if (!$delivery) {
        return back()->with('error', __('Order not found or not assigned to you'));
    }

    // 2️⃣ Mark order as accepted
    $delivery->status = 'accepted';
    $delivery->save();

    // 3️⃣ Get the order
    $order = Order::find($delivery->order_id);
    if ($order) {
        // 4️⃣ Create a chat record (only if not already exists)
        $existingChat = Chat::where('rider_id', $delivery->rider_id)
                                        ->where('buyer_id', $order->user_id)
                                        ->where('order_id', $order->id)
                                        ->first();

        if (!$existingChat) {
            Chat::create([
                'rider_id' => $delivery->rider_id,
                'buyer_id'  => $order->user_id,
                'order_id' => $order->id,
                'delivery_id' => $delivery->id,

            ]);
        }
    }

    return back()->with('success', __('Successfully accepted this order'));
}

    public function orderReject($id)
    {
        $data = DeliveryRider::where('rider_id', $this->rider->id)->where('id', $id)->first();
        $data->status = 'rejected';
        $data->save();
        return back()->with('success', __('Successfully rejected this order'));
    }
    public function orderComplete($id)
    {
        $data = DeliveryRider::where('rider_id', $this->rider->id)->where('id', $id)->first();
        $data->status = 'delivered';
        $data->save();
        return back()->with('success', __('Successfully Delivered this order'));
    }

    public function availableJobs()
    {
        $rider = $this->rider;
        $jobs = DeliveryJob::where('status', 'available')
            ->whereIn('service_area_id', $rider->serviceAreas->pluck('id'))
            ->with(['order', 'stops'])
            ->latest()
            ->get();

        return view('rider.delivery.available', compact('jobs'));
    }

    public function acceptJob($id)
    {
        $rider = $this->rider;
        $acceptanceService = app(DeliveryAcceptanceService::class);

        try {
            $job = $acceptanceService->acceptJob($id, $rider->id);
            return redirect()->route('rider-delivery-index')->with('success', __('Job Accepted successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function deliveryJobs()
    {
        $rider = $this->rider;
        $jobs = DeliveryJob::where('assigned_rider_id', $rider->id)
            ->with(['order', 'stops'])
            ->latest()
            ->get();

        return view('rider.delivery.index', compact('jobs'));
    }

    public function jobDetails($id)
    {
        $job = DeliveryJob::with(['order', 'stops.seller', 'events'])->findOrFail($id);
        if ($job->assigned_rider_id != $this->rider->id) {
            return redirect()->back()->with('error', __('Unauthorized.'));
        }
        return view('rider.delivery.details', compact('job'));
    }
}

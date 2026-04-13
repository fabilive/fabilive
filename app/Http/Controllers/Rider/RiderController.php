<?php

namespace App\Http\Controllers\Rider;

use App\Models\Chat;
use App\Models\City;
use App\Models\DeliveryJob;
use App\Models\DeliveryRider;
use App\Models\Order;
use App\Models\RiderServiceArea;
use App\Models\ServiceArea;
use App\Services\DeliveryAcceptanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Helpers\PriceHelper;

class RiderController extends RiderBaseController
{
    public function index()
    {
        $user = $this->rider;
        $orders = DeliveryRider::where('rider_id', $this->rider->id)
            ->orderby('id', 'desc')->take(8)->get();

        // Fetch available jobs for all riders (removed service area restriction as per user request)
        $available_jobs = \App\Models\DeliveryJob::where('status', 'available')
            ->with(['order', 'stops'])
            ->latest()
            ->take(20)
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
                'email' => 'unique:riders,email,'.$this->rider->id,
            ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }
        //--- Validation Section Ends
        $input = $request->all();
        $data = $this->rider;
        if ($file = $request->file('photo')) {
            $extensions = ['jpeg', 'jpg', 'png', 'svg'];
            if (! in_array($file->getClientOriginalExtension(), $extensions)) {
                return response()->json(['errors' => ['Image format not supported']]);
            }

            $name = \PriceHelper::ImageCreateName($file);
            $file->move('assets/images/users/', $name);
            if ($data->photo != null) {
                if (file_exists(public_path().'/assets/images/users/'.$data->photo)) {
                    unlink(public_path().'/assets/images/users/'.$data->photo);
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
                    return response()->json(['errors' => [0 => __('Confirm password does not match.')]]);
                }
            } else {
                return response()->json(['errors' => [0 => __('Current password Does not match.')]]);
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
            'service_area_id' => 'required|array',
            'service_area_id.*' => 'exists:service_areas,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()]);
        }

        foreach ($request->service_area_id as $area_id) {
            // Check if already selected to prevent duplicates
            $exists = RiderServiceArea::where('rider_id', $this->rider->id)
                ->where('service_area_id', $area_id)
                ->exists();

            if (! $exists) {
                $service_area = new RiderServiceArea();
                $service_area->rider_id = $this->rider->id;
                $service_area->service_area_id = $area_id;
                $service_area->save();
            }
        }

        return response()->json(__('Successfully created your service area(s)'));
    }

    public function serviceAreaUpdate(Request $request, $id)
    {
        $rules = [
            'service_area_id' => 'required|exists:service_areas,id|unique:rider_service_areas,service_area_id,'.$id.',id,rider_id,'.$this->rider->id,
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
        if ($request->type == 'complete') {
            $orders = DeliveryRider::where('rider_id', $this->rider->id)
                ->where('status', 'delivered')->orderby('id', 'desc')->get();

            return view('rider.orders', compact('orders'));
        } else {
            $orders = DeliveryRider::where('rider_id', $this->rider->id)->with(['order:id,pickup_location,total_delivery_fee,order_number'])
                ->where('status', '!=', 'delivered')->orderby('id', 'desc')->get();

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
        $data = DeliveryRider::with(['order', 'vendor', 'pickup', 'serviceAreas'])
            ->where('rider_id', $this->rider->id)
            ->where('id', $id)
            ->first();

        // dd($data);

        if (! $data) {
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

        if (! $delivery) {
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

            if (! $existingChat) {
                Chat::create([
                    'rider_id' => $delivery->rider_id,
                    'buyer_id' => $order->user_id,
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

        if ($data->order) {
            $updateData = ['status' => 'delivered'];
            if ($data->order->method === 'Cash On Delivery') {
                $updateData['payment_status'] = 'Completed';
            }
            $data->order->update($updateData);
        }

        return back()->with('success', __('Successfully Delivered this order'));
    }

    public function availableJobs()
    {
        $rider = $this->rider;
        $jobs = DeliveryJob::where('status', 'available')
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
        $job = DeliveryJob::with(['order', 'stops.seller', 'events', 'chatThreads'])->findOrFail($id);
        if ($job->assigned_rider_id != $this->rider->id && $job->status !== 'available') {
            return redirect()->back()->with('error', __('Unauthorized.'));
        }

        return view('rider.delivery.details', compact('job'));
    }

    public function updateStopStatus(Request $request, $stopId)
    {
        $stop = \App\Models\DeliveryJobStop::findOrFail($stopId);
        $job = $stop->deliveryJob;

        // Only the assigned rider can update
        if ($job->assigned_rider_id != $this->rider->id) {
            return redirect()->back()->with('error', __('Unauthorized.'));
        }

        $newStatus = $request->input('status');
        $allowedStatuses = ['arrived', 'picked_up', 'delivered', 'failed', 'returned'];
        if (! in_array($newStatus, $allowedStatuses)) {
            return redirect()->back()->with('error', __('Invalid status.'));
        }

        // Update the stop status
        $stop->status = $newStatus;
        if ($newStatus === 'arrived') {
            $stop->arrived_at = now();
        } elseif ($newStatus === 'picked_up') {
            $stop->picked_up_at = now();
        } elseif ($newStatus === 'delivered') {
            $stop->delivered_at = now();
        }
        $stop->save();

        // Sync the DeliveryJob and Order status based on stops progress
        $allStops = $job->stops()->get();
        $allPickups = $allStops->where('type', 'pickup');
        $allDropoffs = $allStops->where('type', 'dropoff');

        if ($newStatus === 'picked_up' && $allPickups->every(fn ($s) => $s->status === 'picked_up')) {
            // All pickups done → move to delivering
            $job->update(['status' => 'delivering']);
            if ($job->order) {
                $job->order->update(['status' => 'on delivery']);
            }
        } elseif ($newStatus === 'picked_up') {
            // At least one pickup done
            $job->update(['status' => 'picking_up']);
            if ($job->order) {
                $job->order->update(['status' => 'picked up']);
            }
        } elseif ($newStatus === 'delivered') {
            // Final delivery done
            $job->update(['status' => 'delivered', 'delivered_at' => now()]);

            if ($job->order) {
                $updateData = ['status' => 'delivered'];
                if ($job->order->method === 'Cash On Delivery') {
                    $updateData['payment_status'] = 'Completed';
                }
                $job->order->update($updateData);
            }
        } elseif ($newStatus === 'failed') {
            // Delivery failed
            $job->update(['status' => 'failed']);
            if ($job->order) {
                $job->order->update(['status' => 'failed delivery']);
            }
        } elseif ($newStatus === 'returned') {
            // Return to seller done
            $job->update(['status' => 'returned', 'returned_at' => now()]);
            if ($job->order) {
                $job->order->update(['status' => 'cancelled']); // As requested: "mark order canceled once they return"
            }
        }

        return redirect()->route('rider-delivery-details', $job->id)
            ->with('success', __('Status updated to: ').ucwords(str_replace('_', ' ', $newStatus)));
    }

    public function updateJobStatus(Request $request, $id)
    {
        $job = DeliveryJob::findOrFail($id);

        if ($job->assigned_rider_id != $this->rider->id) {
            return redirect()->back()->with('error', __('Unauthorized.'));
        }

        $newStatus = $request->input('status');
        $allowed = ['picked_up', 'on_delivery', 'delivered', 'returning'];
        if (! in_array($newStatus, $allowed)) {
            return redirect()->back()->with('error', __('Invalid status.'));
        }

        if ($newStatus === 'picked_up') {
            $job->update(['status' => 'picking_up', 'picked_up_at' => now()]);
            if ($job->order) {
                $job->order->update(['status' => 'picked up']);
            }
        } elseif ($newStatus === 'on_delivery') {
            $job->update(['status' => 'delivering']);
            if ($job->order) {
                $job->order->update(['status' => 'on delivery']);
            }
        } elseif ($newStatus === 'delivered') {
            $job->update(['status' => 'delivered', 'delivered_at' => now()]);

            if ($job->order) {
                $updateData = ['status' => 'delivered'];
                if ($job->order->method === 'Cash On Delivery') {
                    $updateData['payment_status'] = 'Completed';
                }
                $job->order->update($updateData);
            }
        } elseif ($newStatus === 'returning') {
            $job->update(['status' => 'returning']);
            if ($job->order) {
                $job->order->update(['status' => 'returning']);
            }
        }

        return redirect()->back()
            ->with('success', __('Order status updated to: ').ucwords(str_replace('_', ' ', $newStatus)));
    }
}

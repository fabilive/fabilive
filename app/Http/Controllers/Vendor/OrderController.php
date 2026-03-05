<?php
namespace App\Http\Controllers\Vendor;
use App\{
    Models\Order,
    Models\VendorOrder
};
use App\Models\Package;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Datatables;
use App\Services\DeliveryJobService;
use App\Models\DeliveryJob;
class OrderController extends VendorBaseController
{
    public function datatables()
    {
        $user = $this->user;
        $datas = Order::with(array('vendororders' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }))->orderby('id', 'desc')->get()->reject(function ($item) use ($user) {
            if ($item->vendororders()->where('user_id', '=', $user->id)->count() == 0) {
                return true;
            }
            return false;
        });
        return Datatables::of($datas)
            ->editColumn('totalQty', function (Order $data) {
                return $data->vendororders()->where('user_id', '=', $this->user->id)->sum('qty');
            })
            ->editColumn('pay_amount', function (Order $data) {
                $order = Order::findOrFail($data->id);
                $user = $this->user;
                $price = $order->vendororders()->where('user_id', '=', $user->id)->sum('price');
                if ($order->is_shipping == 1 && 0) {
                    $vendor_shipping = json_decode($order->vendor_shipping_id);
                    $user_id = auth()->id();
                    $shipping_id = $vendor_shipping->$user_id;
                    $shipping = Shipping::findOrFail($shipping_id);
                    if ($shipping) {
                        $price = $price + round($shipping->price * $order->currency_value, 2);
                    }
                    $vendor_packing_id = json_decode($order->vendor_packing_id);
                    $packing_id = $vendor_packing_id->$user_id;
                    $packaging = Package::findOrFail($packing_id);
                    if ($packaging) {
                        $price = $price + round($packaging->price * $order->currency_value, 2);
                    }
                }
                return \PriceHelper::showOrderCurrencyPrice(($price-$order->commission), $data->currency_sign);
            })
            ->addColumn('action', function (Order $data) {
                $pending = $data->vendororders()->where('user_id', '=', $this->user->id)->where('status', 'pending')->count() > 0 ? "selected" : "";
                $processing = $data->vendororders()->where('user_id', '=', $this->user->id)->where('status', 'processing')->count() > 0 ? "selected" : "";
                $completed = $data->vendororders()->where('user_id', '=', $this->user->id)->where('status', 'completed')->count() > 0 ? "selected" : "";
                $declined =  $data->vendororders()->where('user_id', '=', $this->user->id)->where('status', 'declined')->count() > 0 ? "selected" : "";
                return '
                                <div class="action-list">
                                <a href="' . route("vendor-order-show", $data->order_number) . '" class="btn btn-primary product-btn"><i class="fa fa-eye"></i>  ' . __("Details") . ' </a>
                                    <select class="vendor-btn  ' . $data->vendororders()->where('user_id', '=', $this->user->id)->first()->status . ' ">
                                    <option value=" ' . route("vendor-order-status", ["id1" => $data->order_number, "status" => "pending"]) . ' "   ' . $pending . '  > ' . __("Pending") . ' </option>
                                    <option value=" ' . route("vendor-order-status", ["id1" => $data->order_number, "status" => "processing"]) . ' "  ' . $processing . '   > ' . __("Processing") . ' </option>
                                    <option value=" ' . route("vendor-order-status", ["id1" => $data->order_number, "status" => "completed"]) . ' "  ' . $completed . '   > ' . __("Completed") . ' </option>
                                    <option value=" ' . route("vendor-order-status", ["id1" => $data->order_number, "status" => "declined"]) . ' "  ' . $declined . '   > ' . __("Declined") . ' </option>
                                    </select>
                                </div>';
            })
            ->rawColumns(['id', 'action'])
            ->toJson(); //--- Returning Json Data To Client Side
    }
    public function index()
    {
        return view('vendor.order.index');
    }
    // public function show($slug)
    // {
    //     $user = $this->user;
    //     $order = Order::where('order_number', '=', $slug)->first();
    //     $cart = json_decode($order->cart, true);
    //     return view('vendor.order.details', compact('user', 'order', 'cart'));
    // }
    public function show($slug)
{
    $user  = $this->user;
    $order = Order::with(['customerCity','shippingCity'])
                  ->where('order_number', $slug)
                  ->firstOrFail();
    $cart  = json_decode($order->cart, true);
    return view('vendor.order.details', compact('user', 'order', 'cart'));
}

    public function license(Request $request, $slug)
    {
        $order = Order::where('order_number', '=', $slug)->first();
        $cart = json_decode($order->cart, true);
        $cart['items'][$request->license_key]['license'] = $request->license;
        $new_cart = json_encode($cart);
        $order->cart = $new_cart;
        $order->update();
        $msg = __('Successfully Changed The License Key.');
        return redirect()->back()->with('license', $msg);
    }

    public function invoice($slug)
    {
        $user = $this->user;
        $order = Order::where('order_number', '=', $slug)->first();
        $cart = json_decode($order->cart, true);;
        return view('vendor.order.invoice', compact('user', 'order', 'cart'));
    }

    public function printpage($slug)
    {
        $user = $this->user;
        $order = Order::where('order_number', '=', $slug)->first();
        $cart = json_decode($order->cart, true);;
        return view('vendor.order.print', compact('user', 'order', 'cart'));
    }

    

    public function status($slug, $status)
    {
        $mainorder = VendorOrder::where('order_number', '=', $slug)->first();
        $order = \App\Models\Order::where('id', $mainorder->order_id)->first();
        if ($mainorder->status == "completed") {
            return redirect()->back()->with('success', __('This Order is Already Completed'));
        } else {
            $user = $this->user;
            VendorOrder::where('order_number', '=', $slug)->where('user_id', '=', $user->id)->update(['status' => $status]);
            
            // If status is "ready", trigger the Delivery System logic
            if ($status === 'ready') {
                $jobService = app(DeliveryJobService::class);
                $job = DeliveryJob::where('order_id', $order->id)->first();
                if (!$job) {
                    $job = $jobService->createJobFromOrder($order);
                }

                $stop = $job->stops()->where('type', 'pickup')->where('seller_id', $user->id)->first();
                if ($stop && $stop->status === 'pending') {
                    $stop->update([
                        'status' => 'ready',
                        'ready_at' => now()
                    ]);
                    $jobService->logEvent($job, 'seller', $user->id, 'seller_marked_ready');

                    // If it was pending_readiness, it's now available for riders
                    if ($job->status === 'pending_readiness') {
                        $jobService->transitionStatus($job, 'available', 'system', null, ['trigger' => 'first_seller_ready']);
                        app(\App\Services\DeliveryDispatchService::class)->dispatchToRiders($job);
                        app(\App\Services\DeliveryDispatchService::class)->remindSellers($job);
                    }
                }
            }
            
            return redirect()->route('vendor-order-index')->with('success', __('Order Status Updated Successfully'));
        }
    }
    
//     public function status($slug, $status)
//     {
//     $mainorder = VendorOrder::where('order_number', '=', $slug)->first();
//     if ($mainorder->status == "completed") {
//         return redirect()->back()->with('success', __('This Order is Already Completed'));
//     }
//     $user = $this->user;
//     VendorOrder::where('order_number', '=', $slug)
//         ->where('user_id', '=', $user->id)
//         ->update(['status' => $status]);
//     if ($status == 'completed') {
//         $order = \App\Models\Order::where('order_number', $slug)->first();
//         if ($order) {
//             $riderDelivery = \App\Models\DeliveryRider::where('order_id', $order->id)
//                 ->where('status', 'delivered')
//                 ->first();
//             if ($riderDelivery) {
//                 $shipping = \App\Models\Shipping::find($order->shipping_id);
//                 if ($shipping) {
//                     $rider = \App\Models\Rider::find($riderDelivery->rider_id);
//                     if ($rider) {
//                         $rider->balance += $shipping->price;
//                         $rider->save();
//                     }
//                 }
//             }
//         }
//     }
//     return redirect()->route('vendor-order-index')->with('success', __('Order Status Updated Successfully'));
// }  

//     public function status($slug, $status)
//     {
//     $mainorder = VendorOrder::where('order_number', '=', $slug)->first();
//     if (!$mainorder) {
//         return redirect()->back()->with('error', __('Order not found.'));
//     }
//     if ($mainorder->status == "completed") {
//         return redirect()->back()->with('success', __('This Order is Already Completed'));
//     }
//     $user = $this->user;
//     VendorOrder::where('order_number', '=', $slug)
//         ->where('user_id', '=', $user->id)
//         ->update(['status' => $status]);
//     if ($status === 'completed') {
//         $order = \App\Models\Order::where('id', $mainorder->order_id)->first();
//         if ($order) {
//             $riderDelivery = \App\Models\DeliveryRider::where('order_id', $order->id)
//                 ->where('status', 'delivered')
//                 ->first();
//             if ($riderDelivery) {
//                 $rider = \App\Models\Rider::find($riderDelivery->rider_id);

//                 if ($rider) {
//                     $rider->balance += $order->shipping_cost;
//                     $rider->save();
//                 }
//             }
//         }
//     }
//     return redirect()->route('vendor-order-index')->with('success', __('Order Status Updated Successfully'));
// }




}

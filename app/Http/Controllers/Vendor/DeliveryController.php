<?php

namespace App\Http\Controllers\Vendor;

use App\Models\DeliveryRider;
use App\Models\Package;
use App\Models\Rider;
use App\Models\RiderServiceArea;
use App\Models\Shipping;
use App\{
    Models\Order
};
use Datatables;
use Illuminate\Http\Request;

class DeliveryController extends VendorBaseController
{
    // iiss ka jo commented code tha woh mieny fabilive k folder mien localdisk E mien put kiya hoa ha.
    public function index()
    {
        return view('vendor.delivery.index');
    }

    public function datatables()
    {
        $user = $this->user;
        $datas = Order::orderby('id', 'desc')
            ->with([
                'customerCity',
                'vendororders',   // vendor-related
                'servicearea',     // 👈 GLOBAL pickup location
            ])
            ->get()
            ->reject(function ($item) use ($user) {
                // vendor filtering ONLY here
                return $item->vendororders()
                    ->where('user_id', $user->id)
                    ->count() == 0;
            });

        // dd($datas);
        // dd($datas);
        return Datatables::of($datas)
            ->editColumn('totalQty', function (Order $data) use ($user) {
                return $data->vendororders()->where('user_id', $user->id)->sum('qty');
            })
            ->editColumn('customer_info', function (Order $data) {
                $info = '<strong>'.__('Name').':</strong> '.$data->customer_name.'<br>'.
                    '<strong>'.__('Email').':</strong> '.$data->customer_email.'<br>'.
                    '<strong>'.__('Phone').':</strong> '.$data->customer_phone.'<br>'.
                    '<strong>'.__('Country').':</strong> '.$data->customer_country.'<br>'.
                    '<strong>'.__('City').':</strong> '.$data->customerCity->city_name.'<br>'.
                    '<strong>'.__('Pickup Location').':</strong> '.optional($data->servicearea)->location.'<br>'.
                    '<strong>'.__('Address').':</strong> '.$data->customer_address.'<br>'.
                    '<strong>'.__('Order Date').':</strong> '.$data->created_at->diffForHumans().'<br>';

                return $info;
            })
            ->editColumn('riders', function (Order $data) {
                $delivery = DeliveryRider::where('order_id', $data->id)
                    ->whereVendorId(auth()->id())
                    ->first();
                if ($delivery) {
                    return '<strong class="display-5">Rider : '.$delivery->rider->name.'</br>
                        Pickup Point : '.$delivery->pickup->location.'</br>
                        Status :
                        <span class="badge badge-dark p-1">'.$delivery->status.'</span>
                        </strong>';
                }

                return '<span class="badge badge-danger p-1">'.__('Not Assigned').'</span>';
            })
            ->editColumn('pay_amount', function (Order $data) use ($user) {
                $order = Order::findOrFail($data->id);
                $price = $order->vendororders()->where('user_id', $user->id)->sum('price');
                $price = round($price * $order->currency_value, 2);
                if ($order->is_shipping == 1) {
                    $vendor_shipping = json_decode($order->vendor_shipping_id, true);
                    $shipping_id = $vendor_shipping[$user->id] ?? null;
                    if ($shipping_id) {
                        $shipping = Shipping::find($shipping_id);
                        if ($shipping) {
                            $price += round($shipping->price * $order->currency_value, 2);
                        }
                    }
                    $vendor_packing_id = json_decode($order->vendor_packing_id, true);
                    $packing_id = $vendor_packing_id[$user->id] ?? null;
                    if ($packing_id) {
                        $packaging = Package::find($packing_id);
                        if ($packaging) {
                            $price += round($packaging->price * $order->currency_value, 2);
                        }
                    }
                }
                $commission = round($order->commission * $order->currency_value, 2);

                return \PriceHelper::showOrderCurrencyPrice(($price - $commission), $data->currency_sign);
            })
            ->addColumn('action', function (Order $data) {
                $delevery = DeliveryRider::where('vendor_id', auth()->id())
                    ->where('order_id', $data->id)
                    ->first();
                if ($delevery && $delevery->status == 'delivered') {
                    return '<div class="action-list">
            <a href="'.route('vendor-order-show', $data->order_number).'" class="btn btn-outline-primary btn-sm">
                <i class="fa fa-eye"></i> '.__('Order View').'
            </a>
        </div>';
                }
                $cartData = json_decode($data->cart, true);
                $firstProd = null;
                if (! empty($cartData['items'])) {
                    foreach ($cartData['items'] as $item) {
                        if (isset($item['user_id']) && $item['user_id'] == auth()->id()) {
                            $firstProd = $item['item']['id'] ?? null;
                            break;
                        }
                    }
                }

                return '<div class="action-list">
        <button data-toggle="modal"
                data-target="#riderList"
                customer-city="'.$data->customer_city.'"
                order_id="'.$data->id.'"
                product_id="'.$firstProd.'"
                class="mybtn1 searchDeliveryRider">
            <i class="fa fa-user"></i> '.__('Assign Delivery').'
        </button>
    </div>';
            })
            ->rawColumns(['id', 'customer_info', 'riders', 'action', 'pay_amount'])
            ->toJson();
    }

    public function findReider(Request $request)
    {
        // dd($request->all());
        $order = \App\Models\Order::findOrFail($request->order_id);
        $serviceAreaId = $order->service_area_id;
        // dd($serviceAreaId);
        // Get all riders for this service area
        $areas = RiderServiceArea::where('service_area_id', $serviceAreaId)->get();
        // dd($areas);
        $ridersData = '<option value="">'.__('Select Rider').'</option>';

        foreach ($areas as $area) {
            $rider = $area->rider;

            // Skip individual riders who have a pending delivery
            $hasPending = \App\Models\DeliveryRider::where('rider_id', $rider->id)
                ->where('status', 'accepted')
                ->exists();
            // dd($hasPending);

            if ($rider->rider_type === 'individual' && $hasPending) {
                continue; // Skip this rider
            }

            $ridersData .= '<option riderName="'.e($rider->name).'"
                                area="'.e($area->serviceArea->location).'"
                                value="'.$area->id.'">'.e($rider->name).'</option>';
        }

        return response()->json(['riders' => $ridersData]);
    }

    public function findReiderSubmit(Request $request)
    {
        $service_area = RiderServiceArea::findOrFail($request->rider_id);
        $delivery = DeliveryRider::where('order_id', $request->order_id)
            ->where('product_id', $request->product_id)
            ->whereVendorId(auth()->id())
            ->first();
        if ($delivery) {
            $delivery->rider_id = $service_area->rider_id;
            $delivery->service_area_id = $service_area->id;
            $delivery->pickup_point_id = $request->pickup_point_id;
            $delivery->phone_number = $request->phone_number;
            $delivery->more_info = $request->more_info;
            $delivery->status = 'pending';
            $delivery->save();
        } else {
            DeliveryRider::create([
                'order_id' => $request->order_id,
                'product_id' => $request->product_id,
                'vendor_id' => auth()->id(),
                'rider_id' => $service_area->rider_id,
                'service_area_id' => $service_area->id,
                'pickup_point_id' => $request->pickup_point_id,
                'phone_number' => $request->phone_number,
                'more_info' => $request->more_info,
                'status' => 'pending',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => __('Rider Assigned Successfully'),
        ]);
    }
}

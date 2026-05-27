<?php

namespace App\Http\Controllers\Vendor;

use App\Models\DeliveryRider;
use App\Models\Package;
use App\Models\Rider;
use App\Models\RiderServiceArea;
use App\Models\Shipping;
use App\Helpers\PriceHelper;
use App\Models\Order;
use Datatables;
use Illuminate\Http\Request;

class DeliveryController extends VendorBaseController
{
    // iiss ka jo commented code tha woh mieny fabilive k folder mien localdisk E mien put kiya hoa ha.
    public function index(Request $request)
    {
        return view('vendor.delivery.index', ['status' => $request->status]);
    }

    public function datatables(Request $request)
    {
        $user = $this->user;
        $query = Order::orderby('id', 'desc')
            ->with([
                'customerCity',
                'vendororders',   // vendor-related
                'servicearea',     // 👈 GLOBAL pickup location
            ])
            ->whereHas('vendororders', function ($q) use ($user) {
                 $q->where('user_id', $user->id);
            });

        if ($request->has('status') && $request->status == 'completed') {
            $query->whereHas('vendororders', function ($q) use ($user) {
                $q->where('user_id', $user->id)->where('status', 'completed');
            });
        }

        $datas = $query;
        // dd($datas);
        return Datatables::of($datas)
            ->editColumn('totalQty', function (Order $data) use ($user) {
                return $data->vendororders()->where('user_id', $user->id)->sum('qty');
            })
            ->editColumn('customer_info', function (Order $data) {
                $info = '<strong>'.__('Country').':</strong> '.$data->customer_country.'<br>'.
                    '<strong>'.__('City').':</strong> '.optional($data->customerCity)->city_name.'<br>'.
                    '<strong>'.__('Pickup Location').':</strong> '.optional($data->servicearea)->location.'<br>'.
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
            ->addColumn('pay_amount', function (Order $data) {
                $user = auth()->user();
                $cartData = json_decode($data->cart, true);
                $sum = 0;
                if (! empty($cartData['items'])) {
                    foreach ($cartData['items'] as $item) {
                        if (isset($item['user_id']) && $item['user_id'] == $user->id) {
                            $sum += $item['price'];
                        }
                    }
                }
                return \PriceHelper::showOrderCurrencyPrice($sum, $data->currency_sign);
            })
            ->addColumn('action', function (Order $data) {
                $delevery = DeliveryRider::where('vendor_id', auth()->id())
                    ->where('order_id', $data->id)
                    ->first();
                if ($delevery) {
                    if ($delevery->status == 'delivered') {
                        return '<div class="action-list">
                <a href="'.route('vendor-order-show', $data->order_number).'" class="btn btn-outline-primary btn-sm mb-1 w-100">
                    <i class="fa fa-eye"></i> '.__('Order View').'
                </a>
            </div>';
                    } elseif (in_array($delevery->status, ['picked_up', 'on_delivery', 'returning'])) {
                        $phone = $delevery->rider ? $delevery->rider->phone : '';
                        $waPhone = preg_replace('/[^0-9]/', '', $phone);
                        $contactBtn = '';
                        if($waPhone) {
                            $contactBtn = '<a href="https://wa.me/'.$waPhone.'" target="_blank" class="btn btn-outline-success btn-sm mt-1 w-100"><i class="fab fa-whatsapp"></i> '.__('Contact Rider').'</a>';
                        } else {
                            $contactBtn = '<a href="tel:'.$phone.'" class="btn btn-outline-success btn-sm mt-1 w-100"><i class="fas fa-phone"></i> '.__('Contact Rider').'</a>';
                        }
                        
                        $statusText = $delevery->status == 'picked_up' ? __('Picked Up') : ($delevery->status == 'on_delivery' ? __('Out for Delivery') : __('Returning'));
                        $badgeClass = $delevery->status == 'returning' ? 'badge-danger' : 'badge-warning';
                        
                        return '<div class="action-list">
                <a href="'.route('vendor-order-show', $data->order_number).'" class="btn btn-outline-primary btn-sm mb-1 w-100">
                    <i class="fa fa-eye"></i> '.__('Order View').'
                </a>
                <span class="badge '.$badgeClass.' mt-1 d-block w-100">'.$statusText.'</span>
                '.$contactBtn.'
            </div>';
                    } else {
                        return '<div class="action-list">
                <a href="'.route('vendor-order-show', $data->order_number).'" class="btn btn-outline-primary btn-sm mb-1 w-100">
                    <i class="fa fa-eye"></i> '.__('Order View').'
                </a>
                <span class="badge badge-info mt-1 d-block w-100">'.__('Assigned').'</span>
            </div>';
                    }
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

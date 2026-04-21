<?php

namespace App\Http\Controllers\Front;

use App\Models\Cart;
use App\Models\City;
use App\Models\DeliveryFee;
use App\Models\DistanceFee;
use App\Models\Order;
use App\Models\PaymentGateway;
use App\Models\Product;
use App\Models\ServiceArea;
use App\Models\State;
use Auth;
use DB;
use Illuminate\Http\Request;
use Session;
use App\Helpers\PriceHelper;

class CheckoutController extends FrontBaseController
{
    public function calculateDistance(Request $request)
    {
        $cart = Session::get('cart');
        if (! $cart || count($cart->items) === 0) {
            return response()->json(['error' => 'Cart is empty']);
        }

        // Skip delivery fee entirely for all-digital carts
        $allDigital = true;
        foreach ($cart->items as $item) {
            if (($item['item']['type'] ?? '') === 'Physical') {
                $allDigital = false;
                break;
            }
        }
        if ($allDigital) {
            Session::put('cart_delivery_fee', 0);
            return response()->json([
                'total_fee' => 0, 'distance_km' => 0,
                'weight_gram' => 0, 'weight_kg' => 0, 'weight_ton' => 0,
                'distance_fee' => 0, 'gram_fee' => 0, 'kg_fee' => 0, 'ton_fee' => 0,
                'same_area_count' => 0, 'same_area_unit_fee' => 0, 'same_area_fee' => 0,
                'vendor_count' => 0, 'vendor_distances' => [],
                'digital' => true,
            ]);
        }

        // User ka selected service area
        $userArea = ServiceArea::find($request->service_area_id);
        if (! $userArea) {
            return response()->json(['error' => 'Service area not found']);
        }

        $gs = \App\Models\Generalsetting::find(1);
        $sameAreaUnitFee = $gs ? (float) $gs->same_servicearea_delivery_fee : 0;

        $vendorDistances = [];
        $totalGram = 0;
        $totalKg = 0;
        $totalTon = 0;

        foreach ($cart->items as $item) {
            $cartProduct = Product::find($item['item']->id);
            if (! $cartProduct) {
                continue;
            }

            // ✅ Product ka service area base
            if ($cartProduct->product_servicearea) {
                $productArea = ServiceArea::find($cartProduct->product_servicearea);
                if ($productArea && $productArea->latitude && $productArea->longitude) {
                    $distance = (float) $this->haversineGreatCircleDistance(
                        $userArea->latitude,
                        $userArea->longitude,
                        $productArea->latitude,
                        $productArea->longitude
                    );

                    $vendorDistances[$cartProduct->product_servicearea] = $distance;
                }
            }

            // ✅ Weight wise fees calculation
            if ($cartProduct->delivery_fee && $cartProduct->delivery_unit) {
                $qty = $item['qty'] ?? 1;
                $weight = (float) $cartProduct->delivery_fee * $qty;

                switch (strtolower($cartProduct->delivery_unit)) {
                    case 'gram':
                    case 'g':
                        $totalGram += $weight;
                        break;
                    case 'kg':
                    case 'kilogram':
                        $totalKg += $weight;
                        break;
                    case 'ton':
                    case 'tons':
                    case 't':
                        $totalTon += $weight;
                        break;
                }
            }
        }

        // ✅ Distance slabs
        $slabs = DistanceFee::select('distance_start_range', 'distance_end_range', 'fee')
            ->orderBy('distance_start_range', 'asc')
            ->get();

        $distanceFeeSum = 0;
        $totalDistance = array_sum($vendorDistances);
        $applicableFee = 0;

        foreach ($slabs as $slab) {
            if ($totalDistance >= $slab->distance_start_range && $totalDistance <= $slab->distance_end_range) {
                $applicableFee = $slab->fee;
                break;
            }
        }
        if ($applicableFee === 0) {
            foreach ($slabs as $slab) {
                if ($totalDistance > $slab->distance_end_range) {
                    $applicableFee = $slab->fee;
                }
            }
        }

        $distanceFeeSum = $applicableFee;

        // ✅ Weight fees
        $gramFee = DeliveryFee::whereRaw('LOWER(weight) = ?', ['gram'])
            ->where('start_range', '<=', $totalGram)
            ->where('end_range', '>=', $totalGram)
            ->value('fee') ?? 0;

        $kgFee = DeliveryFee::whereRaw('LOWER(weight) = ?', ['kg'])
            ->where('start_range', '<=', $totalKg)
            ->where('end_range', '>=', $totalKg)
            ->value('fee') ?? 0;

        $tonFee = DeliveryFee::whereRaw('LOWER(weight) = ?', ['ton'])
            ->where('start_range', '<=', $totalTon)
            ->where('end_range', '>=', $totalTon)
            ->value('fee') ?? 0;

        // ✅ Grand total
        $grandTotal = $distanceFeeSum + $gramFee + $kgFee + $tonFee;

        // ✅ Same service area handling
        $sameAreaCount = 0;
        foreach ($vendorDistances as $areaId => $d) {
            if ($d == 0) {
                $sameAreaCount++;
            }
        }
        $sameAreaTotal = $sameAreaCount * $sameAreaUnitFee;
        $grandTotal += $sameAreaTotal;

        Session::put('cart_delivery_fee', round($grandTotal, 2));

        return response()->json([
            'total_fee' => round($grandTotal, 2),
            'distance_km' => round($totalDistance, 2),
            'weight_gram' => $totalGram,
            'weight_kg' => $totalKg,
            'weight_ton' => $totalTon,
            'distance_fee' => round($distanceFeeSum, 2),
            'gram_fee' => round($gramFee, 2),
            'kg_fee' => round($kgFee, 2),
            'ton_fee' => round($tonFee, 2),
            'same_area_count' => $sameAreaCount,
            'same_area_unit_fee' => round($sameAreaUnitFee, 2),
            'same_area_fee' => round($sameAreaTotal, 2),
            'vendor_count' => count($vendorDistances),
            'vendor_distances' => $vendorDistances,
        ]);
    }

    private function haversineGreatCircleDistance($lat1, $lon1, $lat2, $lon2, $earthRadius = 6371)
    {
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
        $angle = 2 * asin(sqrt(
            pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
        ));

        return $angle * $earthRadius; // km
    }

    public function loadpayment($slug1, $slug2)
    {

        $curr = $this->curr;
        $payment = $slug1;
        $pay_id = $slug2;
        $gateway = '';
        if ($pay_id != 0) {
            $gateway = PaymentGateway::findOrFail($pay_id);
        }

        return view('load.payment', compact('payment', 'pay_id', 'gateway', 'curr'));
    }

    public function walletcheck()
    {
        $amount = (float) $_GET['code'];
        $total = (float) $_GET['total'];
        $balance = Auth::user()->balance;
        if ($amount <= $balance) {
            if ($amount > 0 && $amount <= $total) {
                $total -= $amount;
                $data[0] = $total;
                $data[1] = $amount;
                $data[2] = \PriceHelper::showCurrencyPrice($total);
                $data[3] = \PriceHelper::showCurrencyPrice($amount);

                return response()->json($data);
            } else {
                return response()->json(0);
            }
        } else {
            return response()->json(0);
        }
    }

    public function checkout()
    {
        if (! Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', __("You don't have any product to checkout."));
        }
        $dp = 1;
        $vendor_shipping_id = 0;
        $vendor_packing_id = 0;
        $curr = $this->curr;
        $gateways = PaymentGateway::scopeHasGateway($this->curr->id);
        // dd($gateways);
        $pickups = DB::table('pickups')->get();
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $products = $cart->items;
        $service_areas = ServiceArea::all();
        $paystack = PaymentGateway::whereKeyword('paystack')->first();
        $paystackData = $paystack ? $paystack->convertAutoData() : ['key' => ''];
        // dd(Auth::user());
        if (Auth::check()) {
            if ($this->gs->multiple_shipping == 1) {
                $ship_data = Order::getShipData($cart);
                $shipping_data = $ship_data['shipping_data'];
                $vendor_shipping_id = $ship_data['vendor_shipping_id'];
            } else {
                $shipping_data = DB::table('shippings')->whereUserId(0)->get();
            }
            if ($this->gs->multiple_shipping == 1) {
                $pack_data = Order::getPackingData($cart);
                $package_data = $pack_data['package_data'];
                $vendor_packing_id = $pack_data['vendor_packing_id'];
            } else {
                $package_data = DB::table('packages')->whereUserId(0)->get();
            }
            foreach ($products as $prod) {
                if ($prod['item']['type'] == 'Physical') {
                    $dp = 0;
                    break;
                }
            }
            $total = $cart->totalPrice;
            $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
            if (! Session::has('coupon_total')) {
                $total = $total - $coupon;
                $total = $total + 0;
            } else {
                $total = Session::get('coupon_total');
                if (is_string($total)) {
                    $total = (float) preg_replace('/[^0-9\.]/ui', '', $total);
                }
            }
            $service_areas = ServiceArea::all();

            return view('frontend.checkout', ['products' => $cart->items, 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id, 'paystack' => $paystackData, 'service_areas' => $service_areas]);
        } else {
            if ($this->gs->guest_checkout == 1) {
                if ($this->gs->multiple_shipping == 1) {
                    $ship_data = Order::getShipData($cart);
                    $shipping_data = $ship_data['shipping_data'];
                    $vendor_shipping_id = $ship_data['vendor_shipping_id'];
                } else {
                    $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                }
                if ($this->gs->multiple_shipping == 1) {
                    $pack_data = Order::getPackingData($cart);
                    $package_data = $pack_data['package_data'];
                    $vendor_packing_id = $pack_data['vendor_packing_id'];
                } else {
                    $package_data = DB::table('packages')->whereUserId('0')->get();
                }
                foreach ($products as $prod) {
                    if ($prod['item']['type'] == 'Physical') {
                        $dp = 0;
                        break;
                    }
                }
                $total = $cart->totalPrice;
                $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
                if (! Session::has('coupon_total')) {
                    $total = $total - $coupon;
                    $total = $total + 0;
                } else {
                    $total = Session::get('coupon_total');
                    if (is_string($total)) {
                        $total = (float) preg_replace('/[^0-9\.]/ui', '', $total);
                    }
                }
                foreach ($products as $prod) {
                    if ($prod['item']['type'] != 'Physical') {
                        if (! Auth::check()) {
                            $ck = 1;

                            return view('frontend.checkout', ['products' => $cart->items, 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id, 'paystack' => $paystackData, 'service_areas' => $service_areas]);
                        }
                    }
                }

                return view('frontend.checkout', ['products' => $cart->items, 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id, 'paystack' => $paystackData, 'service_areas' => $service_areas]);
            } else {
                if ($this->gs->multiple_shipping == 1) {
                    $ship_data = Order::getShipData($cart);
                    $shipping_data = $ship_data['shipping_data'];
                    $vendor_shipping_id = $ship_data['vendor_shipping_id'];
                } else {
                    $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
                }
                if ($this->gs->multiple_packaging == 1) {
                    $pack_data = Order::getPackingData($cart);
                    $package_data = $pack_data['package_data'];
                    $vendor_packing_id = $pack_data['vendor_packing_id'];
                } else {
                    $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
                }
                $total = $cart->totalPrice;
                $coupon = Session::has('coupon') ? Session::get('coupon') : 0;
                if (! Session::has('coupon_total')) {
                    $total = $total - $coupon;
                    $total = $total + 0;
                } else {
                    $total = Session::get('coupon_total');
                    if (is_string($total)) {
                        $total = (float) preg_replace('/[^0-9\.]/ui', '', $total);
                    }
                }
                $ck = 1;

                return view('frontend.checkout', ['products' => $cart->items, 'totalPrice' => $total, 'pickups' => $pickups, 'totalQty' => $cart->totalQty, 'gateways' => $gateways, 'digital' => $dp, 'curr' => $curr, 'shipping_data' => $shipping_data, 'package_data' => $package_data, 'vendor_shipping_id' => $vendor_shipping_id, 'vendor_packing_id' => $vendor_packing_id, 'paystack' => $paystackData, 'service_areas' => $service_areas]);
            }
        }
    }

    public function getState($country_id)
    {
        $states = State::where('country_id', $country_id)->where('status', 1)->get();
        if (Auth::user()) {
            $user_state = Auth::user()->state;
        } else {
            $user_state = 0;
        }
        $html_states = '<option value="" > Select State </option>';
        foreach ($states as $state) {
            if ($state->id == $user_state) {
                $check = 'selected';
            } else {
                $check = '';
            }
            $html_states .= '<option value="'.$state->id.'"   rel="'.$state->country->id.'" '.$check.' >'.$state->state.'</option>';
        }

        return response()->json(['data' => $html_states, 'state' => $user_state]);
    }

    public function getCity(Request $request)
    {
        $cities = City::where('state_id', $request->state_id)->where('status', 1)->get();
        if (Auth::user()) {
            $user_city = Auth::user()->city;
        } else {
            $user_city = 0;
        }
        $html_cities = '<option value="" > Select City </option>';
        foreach ($cities as $city) {
            if ($city->id == $user_city) {
                $check = 'selected';
            } else {
                $check = '';
            }
            // below line commented delete nahi karni kisi surat
            // $html_cities .= '<option value="' . $city->city_name . '"   ' . $check . ' >' . $city->city_name . '</option>';
            $html_cities .= '<option value="'.$city->id.'" '.$check.'>'.$city->city_name.'</option>';

        }

        return response()->json(['data' => $html_cities, 'state' => $user_city]);
    }

    public function getServiceArea(Request $request)
    {
        $areas = ServiceArea::where('city_id', $request->city_id)->where('status', 1)->get();
        $html_areas = '<option value="" > Select Service Area </option>';
        foreach ($areas as $area) {
            $html_areas .= '<option value="'.$area->id.'">'.$area->location.'</option>';
        }

        return response()->json(['data' => $html_areas]);
    }

    // Redirect To Checkout Page If Payment is Cancelled

    public function paycancle()
    {

        return redirect()->route('front.checkout')->with('unsuccess', __('Payment Cancelled.'));
    }

    // Redirect To Success Page If Payment is Comleted

    public function payreturn()
    {
        if (Session::has('tempcart') || Session::has('temporder')) {
            $oldCart = Session::get('tempcart');
            $tempcart = $oldCart ? new Cart($oldCart) : null;
            $order = Session::get('temporder');

            // Fallback: If order is missing from session but user is logged in
            if (!$order && Auth::check()) {
                $order = Order::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
            }

            if (!$order) {
                return redirect()->route('front.cart')->with('unsuccess', __('Order not found.'));
            }

            return view('frontend.success', compact('tempcart', 'order'));
        } else {
            return redirect()->route('front.index');
        }
    }
}

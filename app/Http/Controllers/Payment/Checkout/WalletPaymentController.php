<?php

namespace App\Http\Controllers\Payment\Checkout;

use App\Classes\GeniusMailer;
use App\Helpers\OrderHelper;
use App\Helpers\PriceHelper;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\Reward;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class WalletPaymentController extends CheckoutBaseControlller
{
    public function store(Request $request)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $input = $request->all();
            if ($request->pass_check) {
                $auth = OrderHelper::auth_check($input); // For Authentication Checking
                if (! $auth['auth_success']) {
                    \Illuminate\Support\Facades\DB::rollBack();

                    return redirect()->back()->with('unsuccess', $auth['error_message']);
                }
            }
            if (! Session::has('cart')) {
                return redirect()->route('front.cart')->with('success', __("You don't have any product to checkout."));
            }
            $oldCart = Session::get('cart');
            $cart = new Cart($oldCart);
            $orderCalculate = PriceHelper::getOrderTotal($input, $cart);
            if (! Auth::check()) {
                \Illuminate\Support\Facades\DB::rollBack();

                return redirect()->back()->with('unsuccess', 'Please login to continue');
            } else {
                $user = \App\Models\User::lockForUpdate()->find(Auth::user()->id);
                if ($user->balance < $orderCalculate['total_amount']) {
                    \Illuminate\Support\Facades\DB::rollBack();

                    return redirect()->back()->with('unsuccess', 'You do not have enough balance in your wallet');
                }
            }
            $totalCommission = 0;
            OrderHelper::license_check($cart); // For License Checking
            $t_oldCart = Session::get('cart');
            $t_cart = new Cart($t_oldCart);
            $new_cart = [];
            $new_cart['totalQty'] = $t_cart->totalQty;
            $new_cart['totalPrice'] = $t_cart->totalPrice;
            $new_cart['items'] = $t_cart->items;

            // Skip delivery fee calculations entirely for digital orders
            $isDigitalOrder = ($input['dp'] ?? 0) == 1;

            foreach ($new_cart['items'] as $key => $item) {
                if ($isDigitalOrder) {
                    $new_cart['items'][$key]['delivery_fee'] = 0;
                    continue;
                }
                $product = \App\Models\Product::find($item['item']['id']);
                if (! $product) {
                    continue;
                }
                $qty = $item['qty'] ?? 1;
                $productGram = $productKg = $productTon = 0;
                if ($product->delivery_fee && $product->delivery_unit) {
                    $unit = strtolower($product->delivery_unit);
                    $weight = (float) $product->delivery_fee * $qty;
                    if (in_array($unit, ['gram', 'g'])) {
                        $productGram = $weight;
                    } elseif (in_array($unit, ['kg', 'kilogram'])) {
                        $productKg = $weight;
                    } elseif (in_array($unit, ['ton', 'tons', 't'])) {
                        $productTon = $weight;
                    }
                }
                $perDistanceKm = 0;
                if ($product->product_servicearea) {
                    $userCity = \App\Models\ServiceArea::find($request->input('service_area_id'));
                    $prodCity = \App\Models\ServiceArea::find($product->product_servicearea);
                    if ($userCity && $prodCity) {
                        $perDistanceKm = (float) $this->haversineGreatCircleDistance(
                            $userCity->latitude,
                            $userCity->longitude,
                            $prodCity->latitude,
                            $prodCity->longitude
                        );
                    }
                }
                $distanceFee = 0;
                $slabs = \App\Models\DistanceFee::orderBy('distance_start_range', 'asc')->get();
                foreach ($slabs as $slab) {
                    if ($perDistanceKm >= $slab->distance_start_range && $perDistanceKm <= $slab->distance_end_range) {
                        $distanceFee = $slab->fee;
                        break;
                    }
                    if ($perDistanceKm > $slab->distance_end_range) {
                        $distanceFee = $slab->fee;
                    }
                }
                $gramFee = \App\Models\DeliveryFee::whereRaw('LOWER(weight) = ?', ['gram'])
                    ->where('start_range', '<=', $productGram)
                    ->where('end_range', '>=', $productGram)
                    ->value('fee') ?? 0;
                $kgFee = \App\Models\DeliveryFee::whereRaw('LOWER(weight) = ?', ['kg'])
                    ->where('start_range', '<=', $productKg)
                    ->where('end_range', '>=', $productKg)
                    ->value('fee') ?? 0;
                $tonFee = \App\Models\DeliveryFee::whereRaw('LOWER(weight) = ?', ['ton'])
                    ->where('start_range', '<=', $productTon)
                    ->where('end_range', '>=', $productTon)
                    ->value('fee') ?? 0;
                $perProductFee = round($distanceFee + $gramFee + $kgFee + $tonFee, 2);
                $new_cart['items'][$key]['delivery_fee'] = $perProductFee;
            }
            $gs = Generalsetting::findOrFail(1);

            if ($isDigitalOrder) {
                // Digital order: zero out all delivery fees
                $new_cart['grand_total_fee'] = 0;
                $input['total_delivery_fee'] = 0;
            } else {
            $sameAreaUnitFee = $gs ? (float) $gs->same_servicearea_delivery_fee : 0;
            $vendorDistances = [];
            $totalGram = 0;
            $totalKg = 0;
            $totalTon = 0;
            $userArea = null;
            if ($request->input('service_area_id')) {
                $userArea = \App\Models\ServiceArea::find($request->input('service_area_id'));
            }
            foreach ($new_cart['items'] as $ncItem) {
                $prod = \App\Models\Product::find($ncItem['item']['id']);
                if (! $prod) {
                    continue;
                }
                $qty = $ncItem['qty'] ?? 1;
                if ($prod->delivery_fee && $prod->delivery_unit) {
                    $unit = strtolower($prod->delivery_unit);
                    $weight = (float) $prod->delivery_fee * $qty;
                    if (in_array($unit, ['gram', 'g'])) {
                        $totalGram += $weight;
                    } elseif (in_array($unit, ['kg', 'kilogram'])) {
                        $totalKg += $weight;
                    } elseif (in_array($unit, ['ton', 'tons', 't'])) {
                        $totalTon += $weight;
                    }
                }
                if ($prod->product_servicearea && $userArea) {
                    $prodArea = \App\Models\ServiceArea::find($prod->product_servicearea);
                    if ($prodArea && $prodArea->latitude && $prodArea->longitude && $userArea->latitude && $userArea->longitude) {
                        $distance = (float) $this->haversineGreatCircleDistance(
                            $userArea->latitude, $userArea->longitude,
                            $prodArea->latitude, $prodArea->longitude
                        );
                        $vendorDistances[$prod->product_servicearea] = $distance;
                    }
                }
            }
            $totalDistance = array_sum($vendorDistances);
            $applicableFee = 0;
            $slabs = \App\Models\DistanceFee::orderBy('distance_start_range', 'asc')->get();
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
            $gramFee = \App\Models\DeliveryFee::whereRaw('LOWER(weight) = ?', ['gram'])
                ->where('start_range', '<=', $totalGram)
                ->where('end_range', '>=', $totalGram)
                ->value('fee') ?? 0;
            $kgFee = \App\Models\DeliveryFee::whereRaw('LOWER(weight) = ?', ['kg'])
                ->where('start_range', '<=', $totalKg)
                ->where('end_range', '>=', $totalKg)
                ->value('fee') ?? 0;
            $tonFee = \App\Models\DeliveryFee::whereRaw('LOWER(weight) = ?', ['ton'])
                ->where('start_range', '<=', $totalTon)
                ->where('end_range', '>=', $totalTon)
                ->value('fee') ?? 0;
            $sameAreaCount = 0;
            foreach ($vendorDistances as $areaId => $d) {
                if ($d == 0) {
                    $sameAreaCount++;
                }
            }
            $sameAreaTotal = $sameAreaCount * $sameAreaUnitFee;
            $grandTotalDelivery = $distanceFeeSum + $gramFee + $kgFee + $tonFee + $sameAreaTotal;
            $grandTotalDelivery = round((float) $grandTotalDelivery, 2);
            $new_cart['grand_total_fee'] = $grandTotalDelivery;
            $riderPercentageComission = $gs->rider_percentage_commission;
            $input['total_delivery_fee'] = $new_cart['grand_total_fee'];
            } // end !isDigitalOrder
            foreach ($new_cart['items'] as $key => $cartItem) {
                $itemPriceWithCommission = $cartItem['item_price'];
                $product = $cartItem['item'];
                $priceWithCommission = $product->price;
                $fixed = $gs->fixed_commission;
                $percentage = $gs->percentage_commission;
                $originalPrice = ($priceWithCommission - $fixed) / (1 + $percentage / 100);
                $commission = $priceWithCommission - $originalPrice;
                $totalCommission += $commission * $cartItem['qty'];
            }
            $new_cart = json_encode($new_cart);
            $temp_affilate_users = OrderHelper::product_affilate_check($cart); // For Product Based Affilate Checking
            $affilate_users = $temp_affilate_users == null ? null : json_encode($temp_affilate_users);
            if (isset($orderCalculate['success']) && $orderCalculate['success'] == false) {
                return redirect()->back()->with('unsuccess', $orderCalculate['message']);
            }
            if ($this->gs->multiple_shipping == 0) {
                // below line deduct nahi karni yeah jub shipping_cost ki value database mien store hoon gie tu yeah line uncomment karni ha aor haan shippings table mien price column mien value v fir daini ha.
                // $orderTotal = $orderCalculate['total_amount'];
                $orderTotal = $orderCalculate['total_amount'];
                $shipping = $orderCalculate['shipping'];
                $packeing = $orderCalculate['packeing'];
                $is_shipping = $orderCalculate['is_shipping'];
                $vendor_shipping_ids = $orderCalculate['vendor_shipping_ids'];
                $vendor_packing_ids = $orderCalculate['vendor_packing_ids'];
                $vendor_ids = $orderCalculate['vendor_ids'];
                $input['shipping_title'] = $shipping ? $shipping->title : 'Free Shipping';
                $input['vendor_shipping_id'] = $shipping ? $shipping->id : 0;
                $input['packing_title'] = $packeing ? $packeing->title : 'None';
                $input['vendor_packing_id'] = $packeing ? $packeing->id : 0;
                // $input['shipping_cost'] = $packeing ? $packeing->price : 0;
                $input['packing_cost'] = $packeing ? $packeing->price : 0;
                $input['is_shipping'] = $is_shipping;
                $input['vendor_shipping_ids'] = $vendor_shipping_ids;
                $input['vendor_packing_ids'] = $vendor_packing_ids;
                $input['vendor_ids'] = $vendor_ids;
            } else {
                // $orderTotal = $orderCalculate['total_amount'];
                $orderTotal = $orderCalculate['total_amount'];
                $shipping = $orderCalculate['shipping'];
                $packeing = $orderCalculate['packeing'];
                $is_shipping = $orderCalculate['is_shipping'];
                $vendor_shipping_ids = $orderCalculate['vendor_shipping_ids'];
                $vendor_packing_ids = $orderCalculate['vendor_packing_ids'];
                $vendor_ids = $orderCalculate['vendor_ids'];
                $packing_cost = $orderCalculate['packing_cost'];
                $input['shipping_title'] = $vendor_shipping_ids;
                $input['vendor_shipping_id'] = $vendor_shipping_ids;
                $input['packing_title'] = $vendor_packing_ids;
                $input['vendor_packing_id'] = $vendor_packing_ids;
                $input['packing_cost'] = $packing_cost;
                $input['is_shipping'] = $is_shipping;
                $input['vendor_shipping_ids'] = $vendor_shipping_ids;
                $input['vendor_packing_ids'] = $vendor_packing_ids;
                $input['vendor_ids'] = $vendor_ids;
                // Safely handle unset if needed - although $input['packeging'] might not exist
                if(isset($input['packeging'])) unset($input['packeging']);
            }

            // Robust Address Fallback: Ensure shipping details are filled from customer details if missing
            if(empty($input['shipping_name'])) $input['shipping_name'] = @$input['customer_name'];
            if(empty($input['shipping_email'])) $input['shipping_email'] = @$input['customer_email'];
            if(empty($input['shipping_phone'])) $input['shipping_phone'] = @$input['customer_phone'];
            if(empty($input['shipping_address'])) $input['shipping_address'] = @$input['customer_address'];
            if(empty($input['shipping_city'])) $input['shipping_city'] = @$input['customer_city'];
            if(empty($input['shipping_zip'])) $input['shipping_zip'] = @$input['customer_zip'];
            if(empty($input['shipping_country'])) $input['shipping_country'] = @$input['customer_country'];
            if(empty($input['shipping_state'])) $input['shipping_state'] = @$input['customer_state'];
            $input['customer_whatsapp'] = $request->customer_whatsapp;

            $input['service_area_id'] = $request->service_area_id;
            
            // Populate city fields with Service Area location name if available
            if (!empty($request->service_area_id)) {
                $serviceArea = \App\Models\ServiceArea::find($request->service_area_id);
                if ($serviceArea) {
                    $input['customer_city'] = $serviceArea->location;
                    if (empty($input['shipping_city']) || is_numeric($input['shipping_city'])) {
                        $input['shipping_city'] = $serviceArea->location;
                    }
                }
            }
            $order = new Order;
            $success_url = route('front.payment.return');
            $input['user_id'] = Auth::check() ? Auth::user()->id : null;
            $input['affilate_users'] = $affilate_users;
            $input['cart'] = $new_cart;
            $input['pay_amount'] = $orderTotal / $this->curr->value;
            $input['order_number'] = Str::random(4).time();
            
            // Safety Fallback: If wallet_price is missing or 0 in request but method is Wallet, use orderTotal
            $wallet_pay_amount = $request->wallet_price;
            if(empty($wallet_pay_amount) || $wallet_pay_amount == 0) {
                $wallet_pay_amount = $orderTotal;
            }
            $input['wallet_price'] = $wallet_pay_amount / $this->curr->value;
            
            $input['method'] = 'Wallet';
            $input['payment_status'] = 'Completed';
            if ($input['tax_type'] == 'state_tax') {
                $input['tax_location'] = State::findOrFail($input['tax'])->state;
            } else {
                $input['tax_location'] = Country::findOrFail($input['tax'])->country_name;
            }
            $input['tax'] = Session::get('current_tax');
            if ($input['dp'] == 1) {
                $input['status'] = 'completed';
            }
            if (Session::has('affilate')) {
                $val = $request->total / $this->curr->value;
                $val = $val / 100;
                $sub = $val * $this->gs->affilate_charge;
                if ($temp_affilate_users != null) {
                    $t_sub = 0;
                    foreach ($temp_affilate_users as $t_cost) {
                        $t_sub += $t_cost['charge'];
                    }
                    $sub = $sub - $t_sub;
                }
                if ($sub > 0) {
                    $user = OrderHelper::affilate_check(Session::get('affilate'), $sub, $input['dp']); // For Affiliate Checking
                    $input['affilate_user'] = Session::get('affilate');
                    $input['affilate_charge'] = $sub;
                }
            }
            $cartTotal = $cart->totalPrice; // Get total cart price
            $input['rider_percentage_commission'] = $totalCommission;
            $order->fill($input)->save();

            // 1. Unified Order Finalization (Tracks, Coupons, Rewards, Stock, Session Clear)
            OrderHelper::finalizeOrder($order, $cart);

            // Deduct from wallet balance BEFORE commit
            if ($order->user_id != 0 && $order->wallet_price != 0) {
                OrderHelper::add_to_transaction($order, $order->wallet_price); // Store To Transactions
            }

            Session::put('temporder', $order);
            Session::put('tempcart', $cart);
            
            // Commit database changes
            \Illuminate\Support\Facades\DB::commit();

            // GRACEFUL MAILERS: If mail fails, don't break the success redirect
            try {
                $data = [
                    'to' => $order->customer_email,
                    'type' => 'new_order',
                    'cname' => $order->customer_name,
                    'oamount' => '',
                    'aname' => '',
                    'aemail' => '',
                    'wtitle' => '',
                    'onumber' => $order->order_number,
                ];
                $mailer = new GeniusMailer();
                $mailer->sendAutoOrderMail($data, $order->id);

                $data = [
                    'to' => $this->ps->contact_email,
                    'subject' => 'New Order Recieved!!',
                    'body' => 'Hello Admin!<br>Your store has received a new order.<br>Order Number is '.$order->order_number.'.Please login to your panel to check. <br>Thank you.',
                ];
                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);
            } catch (\Exception $e) {
                // Log the error but allow the user to see the success page
                \Log::warning('Wallet Checkout Mail Error: '.$e->getMessage());
            }

            return redirect($success_url);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();

            return redirect()->back()->with('unsuccess', $e->getMessage());
        }
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
}

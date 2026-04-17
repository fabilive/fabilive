<?php

namespace App\Http\Controllers\Payment\Checkout;

use App\Classes\Campay;
use App\Helpers\OrderHelper;
use App\Helpers\PriceHelper;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Order;
use App\Models\State;
use App\Models\WalletLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CampayController extends CheckoutBaseControlller
{
    public function store(Request $request)
    {
        $input = $request->all();

        if ($request->pass_check) {
            $auth = OrderHelper::auth_check($input); // For Authentication Checking
            if (! $auth['auth_success']) {
                return redirect()->back()->with('unsuccess', $auth['error_message']);
            }
        }

        if (! Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', __("You don't have any product to checkout."));
        }

        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $orderCalculate = PriceHelper::getOrderTotal($input, $cart);

        if (isset($orderCalculate['success']) && ! $orderCalculate['success']) {
            return redirect()->back()->with('unsuccess', $orderCalculate['message']);
        }

        // Total including delivery fee is now calculated inside PriceHelper::getOrderTotal
        $orderTotal = $orderCalculate['total_amount'] ?? 0;

        if ($this->gs->multiple_shipping == 0) {
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
            $input['shipping_cost'] = $shipping ? $shipping->price : 0;
            $input['packing_cost'] = $packeing ? $packeing->price : 0;
            $input['is_shipping'] = $is_shipping;
            $input['vendor_shipping_ids'] = $vendor_shipping_ids;
            $input['vendor_packing_ids'] = $vendor_packing_ids;
            $input['vendor_ids'] = $vendor_ids;
        } else {
            $shipping_cost = $orderCalculate['shipping_cost'];
            $packing_cost = $orderCalculate['packing_cost'];
            $input['shipping_title'] = $orderCalculate['vendor_shipping_ids'];
            $input['vendor_shipping_id'] = $orderCalculate['vendor_shipping_ids'];
            $input['packing_title'] = $orderCalculate['vendor_packing_ids'];
            $input['vendor_packing_id'] = $orderCalculate['vendor_packing_ids'];
            $input['shipping_cost'] = $shipping_cost;
            $input['packing_cost'] = $packing_cost;
            $input['is_shipping'] = 1;
            $input['vendor_shipping_ids'] = $orderCalculate['vendor_shipping_ids'];
            $input['vendor_packing_ids'] = $orderCalculate['vendor_packing_ids'];
            $input['vendor_ids'] = $orderCalculate['vendor_ids'];
        }

        // Digital orders: zero out delivery/shipping fees
        if (($input['dp'] ?? 0) == 1) {
            $input['shipping_cost'] = 0;
            $input['total_delivery_fee'] = 0;
            $input['packing_cost'] = 0;
        }

        $order = new Order;
        $order_number = Str::random(4).time();

        $input['user_id'] = Auth::check() ? Auth::user()->id : null;
        $input['customer_whatsapp'] = $request->customer_whatsapp;
        $input['cart'] = json_encode($cart);
        $input['pay_amount'] = $orderTotal / $this->curr->value;
        $input['order_number'] = $order_number;
        $input['method'] = 'Campay';
        $input['payment_status'] = 'Pending';
        $input['escrow_status'] = 'held';
        
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

        if (! empty($input['tax'])) {
            if ($input['tax_type'] == 'state_tax') {
                $taxState = State::find($input['tax']);
                $input['tax_location'] = $taxState ? $taxState->state : null;
            } else {
                $taxCountry = Country::find($input['tax']);
                $input['tax_location'] = $taxCountry ? $taxCountry->country_name : null;
            }
            $input['tax'] = Session::get('current_tax');
        } else {
            $input['tax_location'] = null;
            $input['tax'] = 0;
        }

        // Persist coupon details from session
        $input['coupon_code'] = Session::get('coupon_code');
        $input['coupon_id'] = Session::get('coupon_id');
        $input['coupon_discount'] = Session::get('coupon');

        $order->fill($input)->save();


        // Vendor and Stock Logic (Consistent with COD/Wallet flow)
        OrderHelper::size_qty_check($cart);
        OrderHelper::stock_check($cart);
        OrderHelper::vendor_order_check($cart, $order);

        try {
            $order->tracks()->create(['title' => 'Pending', 'text' => 'Order placed. Waiting for payment.']);
            $order->notifications()->create();
        } catch (\Exception $e) {
        }

        // Initialize Campay Collection
        $campay = new Campay();
        try {
            $phoneNumber = preg_replace('/[^0-9]/', '', $request->phone); // Convert to digits only (e.g., +237... -> 237...)
            
            if (empty($phoneNumber)) {
                return back()->with('unsuccess', 'Please enter a valid mobile money number.');
            }
            
            $response = $campay->collect(round($order->pay_amount), $phoneNumber, 'Payment for Order #'.$order_number, $order_number);
            
            // Log response for debugging
            \Log::info('Campay Response for Order #'.$order_number.': '.json_encode($response));

            if (isset($response['reference'])) {
                $order->txnid = $response['reference'];
                $order->update();

                // Redirect to a waiting page or status check page
                return redirect()->route('front.campay.check', $order->order_number);
            } else {
                $errorMessage = isset($response['detail']) ? $response['detail'] : (isset($response['message']) ? $response['message'] : 'Campay initialization failed.');
                return redirect()->back()->with('unsuccess', 'Error: '.$errorMessage);
            }
        } catch (\Exception $e) {
            \Log::error('Campay Exception for Order #'.$order_number.': '.$e->getMessage());
            return redirect()->back()->with('unsuccess', $e->getMessage());
        }
    }

    /**
     * Check payment status (Can be polled from frontend or used as callback)
     */
    public function checkStatus(Request $request, $order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();

        // 1. If webhook or manual update already completed the order, handle it immediately
        if ($order->payment_status === 'Completed') {
            $cart = json_decode($order->cart, true);
            $cartObject = new Cart($cart);
            
            Session::put('temporder', $order);
            Session::put('tempcart', $cartObject);
            Session::forget('cart');

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'successful',
                    'redirect_url' => route('front.payment.return')
                ]);
            }

            return redirect()->route('front.payment.return')->with('success', 'Payment successful!');
        }

        $campay = new Campay();
        try {
            $status = $campay->getStatus($order->txnid);

            // 2. Case-insensitive status check
            if (isset($status['status']) && strtolower($status['status']) === 'successful') {
                if ($order->payment_status != 'Completed') {
                    $this->finalizeOrder($order);
                }

                // Unified Finalization (Sessions, Coupons, Rewards, etc.)
                OrderHelper::finalizeOrder($order, $cartObject);

                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'successful',
                        'redirect_url' => route('front.payment.return')
                    ]);
                }

                return redirect()->route('front.payment.return')->with('success', 'Payment successful!');
            }
        } catch (\Exception $e) {
            \Log::error('Campay Polling Exception for Order #' . $order_number . ': ' . $e->getMessage());
            $status = ['status' => 'ERROR', 'message' => $e->getMessage()];
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'pending',
                'payment_status' => $status
            ]);
        }

        return view('frontend.campay_waiting', compact('order', 'status'));
    }

    protected function finalizeOrder($order)
    {
        $order->payment_status = 'Completed';
        // Auto-complete digital orders (no delivery needed)
        if ($order->dp == 1) {
            $order->status = 'completed';
        }
        $order->update();

        // Add to Wallet Ledger (Escrow Hold)
        WalletLedger::create([
            'user_id' => $order->user_id ?? 0,
            'amount' => $order->pay_amount,
            'type' => 'escrow_hold',
            'order_id' => $order->id,
            'reference' => $order->txnid,
            'status' => 'completed',
            'details' => 'Payment held in escrow via Campay',
        ]);

        // Stock and vendor logic already handled in store() to ensure visibility

        // Add to Transaction Logs (for Admin/Financial visibility)
        try {
            $transaction = new \App\Models\Transaction;
            $transaction->txn_number = \Illuminate\Support\Str::random(3).substr(time(), 6, 8).\Illuminate\Support\Str::random(3);
            $transaction->user_id = $order->user_id;
            $transaction->amount = $order->pay_amount;
            $transaction->currency_sign = $order->currency_sign;
            $transaction->currency_code = $order->currency_name;
            $transaction->currency_value = $order->currency_value;
            $transaction->details = 'Payment Via Campay';
            $transaction->type = 'plus'; // 'plus' because it's an external payment coming into the system/merchant log
            $transaction->save();
        } catch (\Exception $e) {
            \Log::error('Campay Transaction Error: '.$e->getMessage());
        }

        // Notifications and Mail
        try {
            $order->tracks()->create(['title' => 'Paid', 'text' => 'Payment confirmed via Campay.']);
            $order->notifications()->create();
        } catch (\Exception $e) {
        }

        // mailer logic here (similar to WalletPaymentController)
    }
}

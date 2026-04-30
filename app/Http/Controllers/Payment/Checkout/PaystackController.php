<?php

namespace App\Http\Controllers\Payment\Checkout;

use App\Classes\GeniusMailer;
use App\Models\Cart;
use App\Models\Country;
use App\Models\Order;
use App\Models\Reward;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\PriceHelper;
use App\Helpers\OrderHelper;
use Session;

class PaystackController extends CheckoutBaseControlller
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
        OrderHelper::license_check($cart); // For License Checking
        $t_oldCart = Session::get('cart');
        $t_cart = new Cart($t_oldCart);
        $new_cart = [];
        $new_cart['totalQty'] = $t_cart->totalQty;
        $new_cart['totalPrice'] = $t_cart->totalPrice;
        $new_cart['items'] = $t_cart->items;
        $new_cart = json_encode($new_cart);
        $temp_affilate_users = OrderHelper::product_affilate_check($cart); // For Product Based Affilate Checking
        $affilate_users = $temp_affilate_users == null ? null : json_encode($temp_affilate_users);

        $orderCalculate = PriceHelper::getOrderTotal($input, $cart);
        // dd($orderCalculate,'multi');
        if (isset($orderCalculate['success']) && $orderCalculate['success'] == false) {
            return redirect()->back()->with('unsuccess', $orderCalculate['message']);
        }

        $input['total_delivery_fee'] = $orderCalculate['delivery_fee'];
        if ($this->gs->multiple_shipping == 0) {
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
            $input['shipping_cost'] = $shipping ? $shipping->price : 0;
            $input['packing_cost'] = $packeing ? $packeing->price : 0;
            $input['is_shipping'] = $is_shipping;
            $input['vendor_shipping_ids'] = $vendor_shipping_ids;
            $input['vendor_packing_ids'] = $vendor_packing_ids;
            $input['vendor_ids'] = $vendor_ids;

        } else {

            // multi shipping

            $orderTotal = $orderCalculate['total_amount'];
            $shipping = $orderCalculate['shipping'];
            $packeing = $orderCalculate['packeing'];
            $is_shipping = $orderCalculate['is_shipping'];
            $vendor_shipping_ids = $orderCalculate['vendor_shipping_ids'];
            $vendor_packing_ids = $orderCalculate['vendor_packing_ids'];
            $vendor_ids = $orderCalculate['vendor_ids'];
            $shipping_cost = $orderCalculate['shipping_cost'];
            $packing_cost = $orderCalculate['packing_cost'];

            $input['shipping_title'] = $vendor_shipping_ids;
            $input['vendor_shipping_id'] = $vendor_shipping_ids;
            $input['packing_title'] = $vendor_packing_ids;
            $input['vendor_packing_id'] = $vendor_packing_ids;
            $input['shipping_cost'] = $shipping_cost;
            $input['packing_cost'] = $packing_cost;
            $input['is_shipping'] = $is_shipping;
            $input['vendor_shipping_ids'] = $vendor_shipping_ids;
            $input['vendor_packing_ids'] = $vendor_packing_ids;
            $input['vendor_ids'] = $vendor_ids;
            unset($input['shipping']);
            unset($input['packeging']);
        }

        $order = new Order;
        $success_url = route('front.payment.return');
        $input['user_id'] = Auth::check() ? Auth::user()->id : null;
        $input['cart'] = $new_cart;
        $input['affilate_users'] = $affilate_users;
        $input['wallet_price'] = ($request->wallet_price ?? 0) / $this->curr->value;
        $input['pay_amount'] = ($orderTotal - ($input['wallet_price'] * $this->curr->value)) / $this->curr->value;
        $input['order_number'] = Str::random(4).time();
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

        $order->fill($input)->save();
        
        // Use Unified Order Finalization (Handles tracks, coupons, rewards, stock, wallet deduction, and session clearing)
        OrderHelper::finalizeOrder($order, $cart);

        //Sending Email To Buyer
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

        //Sending Email To Admin
        $data = [
            'to' => $this->ps->contact_email,
            'subject' => 'New Order Recieved!!',
            'body' => 'Hello Admin!<br>Your store has received a new order.<br>Order Number is '.$order->order_number.'.Please login to your panel to check. <br>Thank you.',
        ];
        $mailer = new GeniusMailer();
        $mailer->sendCustomMail($data);

        return redirect($success_url);
    }
}

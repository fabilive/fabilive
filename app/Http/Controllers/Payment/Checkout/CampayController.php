<?php

namespace App\Http\Controllers\Payment\Checkout;

use App\{
    Models\Cart,
    Models\Order,
    Classes\GeniusMailer,
    Classes\Campay
};
use App\Helpers\OrderHelper;
use App\Helpers\PriceHelper;
use App\Models\Country;
use App\Models\Reward;
use App\Models\State;
use App\Models\WalletLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Generalsetting;

class CampayController extends CheckoutBaseControlller
{
    public function store(Request $request)
    {
        $input = $request->all();

        if ($request->pass_check) {
            $auth = OrderHelper::auth_check($input); // For Authentication Checking
            if (!$auth['auth_success']) {
                return redirect()->back()->with('unsuccess', $auth['error_message']);
            }
        }

        if (!Session::has('cart')) {
            return redirect()->route('front.cart')->with('success', __("You don't have any product to checkout."));
        }

        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $orderCalculate = PriceHelper::getOrderTotal($input, $cart);

        // Calculate total including delivery fee (logic from WalletPaymentController)
        $orderTotal = $orderCalculate['total_amount'] + ($input['total_delivery_fee'] ?? 0);

        $order = new Order;
        $order_number = Str::random(4) . time();
        
        $input['user_id'] = Auth::check() ? Auth::user()->id : NULL;
        $input['cart'] = json_encode($cart);
        $input['pay_amount'] = $orderTotal / $this->curr->value;
        $input['order_number'] = $order_number;
        $input['method'] = "Campay";
        $input['payment_status'] = "Pending";
        $input['escrow_status'] = "held";

        if ($input['tax_type'] == 'state_tax') {
            $input['tax_location'] = State::findOrFail($input['tax'])->state;
        } else {
            $input['tax_location'] = Country::findOrFail($input['tax'])->country_name;
        }
        $input['tax'] = Session::get('current_tax');

        $order->fill($input)->save();
        $order->tracks()->create(['title' => 'Pending', 'text' => 'Order placed. Waiting for payment.']);

        // Initialize Campay Collection
        $campay = new Campay();
        try {
            $phoneNumber = $request->phone; // Assuming phone is provided in checkout
            $response = $campay->collect($order->pay_amount, $phoneNumber, 'Payment for Order #' . $order_number, $order_number);
            
            if (isset($response['reference'])) {
                $order->txnid = $response['reference'];
                $order->update();
                
                // Redirect to a waiting page or status check page
                return redirect()->route('front.campay.check', $order->order_number);
            } else {
                return redirect()->back()->with('unsuccess', 'Campay initialization failed: ' . json_encode($response));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('unsuccess', $e->getMessage());
        }
    }

    /**
     * Check payment status (Can be polled from frontend or used as callback)
     */
    public function checkStatus($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        $campay = new Campay();
        $status = $campay->getStatus($order->txnid);

        if (isset($status['status']) && $status['status'] == 'SUCCESSFUL') {
            if ($order->payment_status != 'Completed') {
                $this->finalizeOrder($order);
            }
            return redirect()->route('front.payment.return')->with('success', 'Payment successful!');
        }

        return view('frontend.campay_waiting', compact('order', 'status'));
    }

    protected function finalizeOrder($order)
    {
        $order->payment_status = 'Completed';
        $order->update();

        // Add to Wallet Ledger (Escrow Hold)
        WalletLedger::create([
            'user_id' => $order->user_id ?? 0,
            'amount' => $order->pay_amount,
            'type' => 'escrow_hold',
            'order_id' => $order->id,
            'reference' => $order->txnid,
            'status' => 'completed',
            'details' => 'Payment held in escrow via Campay'
        ]);

        // Stock and vendor logic
        $cart = json_decode($order->cart, true);
        OrderHelper::size_qty_check($cart);
        OrderHelper::stock_check($cart);
        OrderHelper::vendor_order_check($cart, $order);

        // Notifications and Mail
        $order->tracks()->create(['title' => 'Paid', 'text' => 'Payment confirmed via Campay.']);
        $order->notifications()->create();
        
        // mailer logic here (similar to WalletPaymentController)
    }
}

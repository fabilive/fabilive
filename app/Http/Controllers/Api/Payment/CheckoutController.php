<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Front\FrontBaseController;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\Order;
use App\Models\PaymentGateway;
use App\Services\CampayService;
use DB;
use Illuminate\Http\Request;
use Session;

class CheckoutController extends FrontBaseController
{
    protected $campayService;

    public function __construct(CampayService $campayService)
    {
        $this->campayService = $campayService;
    }

    public function loadpayment(Request $request, $slug1, $slug2)
    {
        if ($request->has('order_number')) {
            $order_number = $request->order_number;
            $order = Order::where('order_number', $order_number)->firstOrFail();
            $curr = Currency::where('sign', '=', $order->currency_sign)->firstOrFail();
            $payment = $slug1;
            $pay_id = $slug2;
            $gateway = '';
            if ($pay_id != 0) {
                $gateway = PaymentGateway::findOrFail($pay_id);
            }

            return view('payment.load.payment', compact('payment', 'pay_id', 'gateway', 'curr'));
        }
    }

    public function depositloadpayment(Request $request, $slug1, $slug2)
    {

        if ($request->has('deposit_number')) {
            $deposit_number = $request->deposit_number;
            $deposit = Deposit::where('deposit_number', $deposit_number)->firstOrFail();
            $curr = Currency::where('name', $deposit->currency_code)->firstOrFail();
            $payment = $slug1;
            $pay_id = $slug2;
            $gateway = '';
            if ($pay_id != 0) {
                $gateway = PaymentGateway::findOrFail($pay_id);
            }

            return view('payment.load.payment', compact('payment', 'pay_id', 'gateway', 'curr'));
        }
    }

    public function checkout(Request $request)
    {
        if ($request->has('order_number')) {
            $order_number = $request->order_number;
            $order = Order::where('order_number', $order_number)->firstOrFail();
            $package_data = DB::table('packages')->where('user_id', '=', 0)->get();
            $shipping_data = DB::table('shippings')->where('user_id', '=', 0)->get();
            $curr = Currency::where('sign', '=', $order->currency_sign)->firstOrFail();
            $gateways = collect();
            $campay = PaymentGateway::where('name', 'Campay')->first();
            if ($campay && $campay->title && $campay->showCheckoutLink()) {
                $gateways->push($campay);
            }
            $paystack = PaymentGateway::whereKeyword('paystack')->first();
            $paystackData = $paystack->convertAutoData();
            //$paystackData = $paystack ? $paystack->convertAutoData() : null;
            if ($order->payment_status == 'Pending') {
                return view('payment.checkout', compact('order', 'package_data', 'shipping_data', 'gateways', 'paystackData'));
            }
        }
    }

    //     public function checkout(Request $request)
    // {
    //     if ($request->has('order_number')) {
    //         $order_number = $request->order_number;
    //         $order = Order::where('order_number', $order_number)->firstOrFail();

    //         // Only process pending orders
    //         if ($order->payment_status !== 'Pending') {
    //             return redirect()->back()->with('unsuccess', 'Order is already paid.');
    //         }

    //         // Campay only
    //         $campay = PaymentGateway::where('name', 'Campay')->first();
    //         if (!$campay || !$campay->showCheckoutLink()) {
    //             return redirect()->back()->with('unsuccess', 'Campay is not available.');
    //         }

    //         // Generate Campay link via service
    //         $response = $this->campayService->generatePaymentLink(
    //             $order->pay_amount * $order->currency_value,    // Amount in correct currency
    //             $order->currency_code,                          // e.g., "XAF"
    //             'Order Payment - #' . $order->order_number,
    //             route('front.campay.notify')                    // Redirect/notify callback
    //         );

    //         // Check and redirect
    //         if (isset($response['link'])) {
    //             Session::put('order_data', $order); // if needed
    //             return redirect()->away($response['link']);
    //         }

    //         return redirect()->back()->with('unsuccess', 'Failed to generate Campay payment link.');
    //     }

    //     return redirect()->back()->with('unsuccess', 'Order number missing.');
    // }

}

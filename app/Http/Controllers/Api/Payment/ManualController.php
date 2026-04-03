<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Order;
use App\Models\PaymentGateway;
use App\Services\CampayService;
use Illuminate\Http\Request;
use Session;

class ManualController extends Controller
{
    public function __construct(CampayService $campayService)
    {
        $this->campayService = $campayService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'txnid' => 'required',
        ]);
        if ($request->has('order_number')) {
            $order_number = $request->order_number;
            $order = Order::where('order_number', $order_number)->firstOrFail();
            $item_amount = $order->pay_amount * $order->currency_value;
            $order->pay_amount = round($item_amount / $order->currency_value, 2);
            $order->method = $request->method;
            $order->txnid = $request->txnid;
            $order->payment_status = 'Pending';
            $order->save();

            $campay = PaymentGateway::where('name', 'Campay')->first();
            if (! $campay || ! $campay->showCheckoutLink()) {
                return redirect()->back()->with('unsuccess', 'Campay is not available.');
            }

            // Generate Campay link via service
            $response = $this->campayService->generatePaymentLink(
                $order->pay_amount * $order->currency_value,    // Amount in correct currency
                $order->currency_code,                          // e.g., "XAF"
                'Order Payment - #'.$order->order_number,
                route('front.campay.notify')                    // Redirect/notify callback
            );

            // Check and redirect
            if (isset($response['link'])) {
                Session::put('order_data', $order); // if needed

                return redirect()->away($response['link']);
            }

            return redirect()->back()->with('unsuccess', 'Failed to generate Campay payment link.');

            return redirect(route('front.payment.success', 1));
        } else {
            return redirect()->back()->with('unsuccess', 'Something Went Wrong.');
        }
    }
}

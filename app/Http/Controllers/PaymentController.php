<?php

namespace App\Http\Controllers;

use App\Services\HitPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function createPayment(HitPayService $hitPayService)
    {
        $payment = $hitPayService->createPaymentRequest(20.00, 'SGD', 'Test Purchase');

        if (isset($payment['url'])) {
            return redirect($payment['url']); // Redirect user to HitPay checkout
        }

        return back()->with('error', 'Unable to create payment.');
    }

    public function handleCallback(Request $request, HitPayService $hitPayService)
    {
        $paymentId = $request->get('id');

        // Use HitPay API to fetch and verify payment status
        $response = Http::withHeaders([
            'X-BUSINESS-API-KEY' => config('services.hitpay.api_key'),
        ])->get(config('services.hitpay.base_url')."/v1/payment-requests/{$paymentId}");

        $paymentDetails = $response->json();

        return view('payment.callback', ['data' => $paymentDetails]);
    }
}

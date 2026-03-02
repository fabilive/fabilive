<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\PaymentGateway;
class HitPayService
{
    private $apiKey;
    private $salt;
    private $url;
    private $callback;

    public function __construct()
    {
        $data = PaymentGateway::whereKeyword('hitpay')->first();
        $paydata = $data->convertAutoData();
        $this->apiKey = $paydata['api_key'];
        $this->secret = $paydata['salt'];
        // $this->baseUrl = 'https://api.hit-pay.com'; // for-live
        $this->baseUrl = 'https://api.sandbox.hit-pay.com';
        $this->callback = route('deposit.hitpay.notify');
    }

    public function createPaymentRequest($amount, $currency = 'SGD', $purpose = 'Deposit')
    {
        // dd($currency);
        // Construct the full URL
        $fullUrl = "{$this->baseUrl}/v1/payment-requests";
        //return $fullUrl;

        $response = Http::withHeaders([
            'X-BUSINESS-API-KEY' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($fullUrl, [
            'amount' => $amount,
            'currency' => $currency,
            'purpose' => $purpose,
            'redirect_url' => $this->callback,
            'webhook' => $this->callback,
            //'payment_methods' => ['card'],
        ]);

        return $response->json();
    }
    
    public function generatePaymentLink($amount, $currency = 'SGD', $description = 'Deposit', $redirectUrl = null)
    {
        $fullUrl = "{$this->baseUrl}/v1/payment-requests";

       $response = Http::withHeaders([
            'X-BUSINESS-API-KEY' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($fullUrl, [
            'amount' => $amount,
            'currency' => $currency,
            'purpose' => $description,
            'redirect_url' => $redirectUrl,
            'webhook' => $redirectUrl,
            //'payment_methods' => ['card'],
        ]);

        return $response->json();

        \Log::info('HitPay Checkout Raw Response:', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        if ($response->failed()) {
            \Log::error('HitPay API Error:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        return $response->json();
    }
}

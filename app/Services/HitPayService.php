<?php

namespace App\Services;

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;

class HitPayService
{
    private $apiKey;

    private $salt;

    private $url;

    private $callback;

    public function __construct()
    {
        $data = PaymentGateway::whereKeyword('hitpay')->first();
        if ($data) {
            $paydata = $data->convertAutoData();
            $this->apiKey = isset($paydata['api_key']) ? $paydata['api_key'] : '';
            $this->secret = isset($paydata['salt']) ? $paydata['salt'] : '';
        } else {
            $this->apiKey = '';
            $this->secret = '';
        }

        // $this->baseUrl = 'https://api.hit-pay.com'; // for-live
        $this->baseUrl = 'https://api.sandbox.hit-pay.com';
        try {
            $this->callback = route('deposit.hitpay.notify');
        } catch (\Exception $e) {
            $this->callback = '';
        }
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

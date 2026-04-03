<?php

namespace App\Services;

use App\Models\PaymentGateway;
use Illuminate\Support\Facades\Http;

class CampayService
{
    private $apiKey;

    private $secret;

    private $baseUrl;

    public function __construct()
    {
        $data = PaymentGateway::whereKeyword('campay')->first();
        $paydata = $data ? $data->convertAutoData() : [];

        $this->apiKey = $paydata['username'] ?? '';
        $this->secret = $paydata['password'] ?? '';
        $this->baseUrl = 'https://www.campay.net/api';
    }

    private function getToken()
    {
        $response = Http::post("{$this->baseUrl}/token/", [
            'username' => $this->apiKey,  // Correct field name
            'password' => $this->secret,  // Correct field name
        ]);

        // Optional: debug the response
        // dd([
        //     'status' => $response->status(),
        //     'body' => $response->body(),
        //     'json' => $response->json(),
        //     'username_sent' => $this->apiKey,
        //     'password_length' => strlen($this->secret),
        // ]);

        if ($response->failed()) {
            \Log::error('Campay Auth Failed', ['body' => $response->body()]);
            throw new \Exception('Failed to authenticate with Campay.');
        }

        $data = $response->json();

        if (! isset($data['token'])) {
            \Log::error('Campay Auth Response Missing Token', ['body' => $data]);
            throw new \Exception('Campay token not found.');
        }

        return $data['token'];
    }

    public function initiateCheckout($currency, $amount, $from, $description, $callbackUrl)
    {
        $token = $this->getToken();
        if (! $token) {
            \Log::error('Failed to get Campay access token.');

            return null;
        }
        $payload = [
            'amount' => $amount,
            'currency' => $currency,
            'from' => $from,
            'description' => $description,
            'callback_url' => $callbackUrl,
        ];
        \Log::info('Campay Checkout Payload:', $payload);
        $response = Http::withHeaders([
            'Authorization' => 'Token '.$token,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/collect/", [
            'amount' => $amount,
            'from' => $from,
            'description' => $description,
            'callback_url' => $callbackUrl,
            'external_reference' => '',
        ]);
        \Log::info('Campay Checkout Raw Response:', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
        if ($response->failed()) {
            \Log::error('Campay API Error:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }
        $responseData = $response->json();
        if ($responseData === null) {
            \Log::error('Campay API returned an invalid or empty JSON response.');

            return null;
        }
        \Log::info('Campay Checkout Response:', $responseData);

        return $responseData;
    }

    public function generatePaymentLink($currency, $amount, $description, $redirectUrl = null)
    {
        $token = $this->getToken();
        if (! $token) {
            return ['error' => 'Failed to get access token'];
        }
        $response = Http::withHeaders([
            'Authorization' => 'Token '.$token,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/get_payment_link/", [
            'amount' => $amount,
            'description' => $description,
            'redirect_url' => $redirectUrl,
            'external_reference' => '',
        ]);

        \Log::info('Campay Checkout Raw Response:', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
        if ($response->failed()) {
            \Log::error('Campay API Error:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }
        $responseData = $response->json();

        return $responseData;
    }

    public function requestPayment($amount, $phone, $firstName, $email, $description, $currency = 'XAF', $externalReference = null)
    {
        $token = $this->getToken();
        $response = Http::withHeaders([
            'Authorization' => 'Token '.$token,
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/collect/", [
            'amount' => $amount,
            'from' => $phone,
            'first_name' => $firstName,
            'email' => $email,
            'description' => $description,
            'external_reference' => '',
        ]);

        return $response->json();
    }

    public function checkPaymentStatus($reference)
    {
        $token = $this->getToken();

        $response = Http::withToken($token)->get("{$this->baseUrl}/transaction/", [
            'reference' => $reference,
        ]);

        return $response->json();
    }
}

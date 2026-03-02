<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\PaymentGateway;

class CampayService
{
    private $apiKey;
    private $secret;
    private $baseUrl;

    public function __construct()
    {
        $data = PaymentGateway::whereKeyword('campay')->first();
        $paydata = $data->convertAutoData();
        $this->apiKey = $paydata['username'];
        $this->secret = $paydata['password'];
        $this->baseUrl = 'https://demo.campay.net/api';
    }

    /**
     * Authenticate and get the token
     */
    private function getToken()
    {
        $response = Http::post("{$this->baseUrl}/token/", [
            'username' => $this->apiKey,
            'password' => $this->secret,
        ]);

        if ($response->failed()) {
            throw new \Exception('Failed to authenticate with Campay.');
        }

        return $response->json()['token'];
    }

    public function initiateCheckout($currency, $amount, $from, $description, $callbackUrl)
    {
        // Get the access token
        $token = $this->getToken();

        if (!$token) {
            \Log::error('Failed to get Campay access token.');
            return null;
        }

        // Prepare the request payload
        $payload = [
            'amount' => '100',
            'currency' => $currency,
            'from' => $from,
            'description' => $description,
            'callback_url' => $callbackUrl,
        ];

        // Log the payload for debugging
        \Log::info('Campay Checkout Payload:', $payload);

        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $token,
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/collect/", [
            'amount' => '100',
            //$amount,
            'from' => $from,
            //'first_name' => $firstName,
            //'email' => $email,
            'description' => $description,
            'callback_url' => $callbackUrl,
            'external_reference' => '',
        ]);

        // Log the raw response for debugging
        \Log::info('Campay Checkout Raw Response:', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        // Check for API errors
        if ($response->failed()) {
            \Log::error('Campay API Error:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        // Parse the JSON response
        $responseData = $response->json();
        if ($responseData === null) {
            \Log::error('Campay API returned an invalid or empty JSON response.');
            return null;
        }

        // Log the response for debugging
        \Log::info('Campay Checkout Response:', $responseData);

        // Return the response
        return $responseData;
    }

    public function generatePaymentLink($amount, $currency, $description, $redirectUrl = null)
    {
        $token = $this->getToken();

        if (!$token) {
            return ['error' => 'Failed to get access token'];
        }


        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $token,
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/get_payment_link/", [
            'amount' => '100',
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

        // Return the response
        return $responseData;
    }

    /**
     * Make a payment request
     */
    public function requestPayment($amount, $phone, $firstName, $email, $description, $currency = 'XAF', $externalReference = null)
    {
        $token = $this->getToken();

        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $token,
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/collect/", [
            'amount' => '100',
            //$amount,
            'from' => $phone,
            'first_name' => $firstName,
            'email' => $email,
            'description' => $description,
            'external_reference' => '',
        ]);
        dd($response->json());

        return $response->json();
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($reference)
    {
        $token = $this->getToken();

        $response = Http::withToken($token)->get("{$this->baseUrl}/transaction/", [
            'reference' => $reference,
        ]);

        return $response->json();
    }
}

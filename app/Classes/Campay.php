<?php

namespace App\Classes;

use Illuminate\Support\Facades\Http;

class Campay
{
    protected $app_id;

    protected $app_secret;

    protected $base_url;

    protected $token;

    protected $permanent_token;

    public function __construct()
    {
        $gateway = \App\Models\PaymentGateway::where('keyword', 'campay')->first();
        if ($gateway && $gateway->information) {
            $info = json_decode($gateway->information, true);
            $this->app_id = ! empty($info['app_id']) ? $info['app_id'] : (! empty($info['username']) ? $info['username'] : env('CAMPAY_APP_ID'));
            $this->app_secret = ! empty($info['app_secret']) ? $info['app_secret'] : (! empty($info['password']) ? $info['password'] : env('CAMPAY_APP_SECRET'));
            $this->base_url = ! empty($info['base_url']) ? $info['base_url'] : env('CAMPAY_BASE_URL', 'https://www.campay.net/api');
            $this->permanent_token = ! empty($info['permanent_token']) ? $info['permanent_token'] : env('CAMPAY_PERMANENT_TOKEN');
        } else {
            $this->app_id = env('CAMPAY_APP_ID');
            $this->app_secret = env('CAMPAY_APP_SECRET');
            $this->base_url = env('CAMPAY_BASE_URL', 'https://www.campay.net/api');
            $this->permanent_token = env('CAMPAY_PERMANENT_TOKEN');
        }
    }

    /**
     * Get API Token
     */
    public function getToken()
    {
        if ($this->permanent_token) {
            return $this->permanent_token;
        }

        if ($this->token) {
            return $this->token;
        }

        if (empty($this->app_id) || empty($this->app_secret)) {
            \Log::error('Campay Error: Missing App ID or App Secret.');
            throw new \Exception('Campay Authentication Failed: Missing credentials. Please check Admin Panel > Payment Settings.');
        }

        // Masked logging for debugging
        $maskedId = substr($this->app_id, 0, 4).'...'.substr($this->app_id, -4);
        \Log::info('Campay: Attempting to get temporary token with ID: '.$maskedId);

        $response = Http::asJson()->post($this->base_url.'/token/', [
            'username' => $this->app_id,
            'password' => $this->app_secret,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['token'])) {
                $this->token = $data['token'];

                return $this->token;
            }
        }

        \Log::error('Campay Auth Error Response: '.$response->body());
        throw new \Exception('Campay Authentication Failed: '.$response->body());
    }

    /**
     * Helper to get request with correct header
     */
    protected function request()
    {
        $token = $this->getToken();
        $header = $this->permanent_token ? 'Token' : 'Bearer';

        return Http::withHeaders(['Authorization' => $header.' '.$token]);
    }

    /**
     * Collect payment from customer
     */
    public function collect($amount, $phoneNumber, $description = 'Fabilive Order', $externalReference = null)
    {
        $response = $this->request()->post($this->base_url.'/collect/', [
            'amount' => $amount,
            'from' => $phoneNumber,
            'description' => $description,
            'external_reference' => $externalReference,
            'currency' => 'XAF',
        ]);

        return $response->json();
    }

    /**
     * Check transaction status
     */
    public function getStatus($reference)
    {
        $response = $this->request()->get($this->base_url.'/transaction/'.$reference.'/');

        return $response->json();
    }

    /**
     * Payout to vendor or delivery agent
     */
    public function withdraw($amount, $phoneNumber, $description = 'Fabilive Withdrawal', $externalReference = null)
    {
        $response = $this->request()->post($this->base_url.'/withdraw/', [
            'amount' => $amount,
            'to' => $phoneNumber,
            'description' => $description,
            'external_reference' => $externalReference,
            'currency' => 'XAF',
        ]);

        return $response->json();
    }
}

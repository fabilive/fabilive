<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\PaymentGateway;
class PesapalService
{
    private $apiKey;
    private $secret;
    private $baseUrl;
    public function __construct()
    {
    $data = PaymentGateway::whereKeyword('pesapal')->first();
    $paydata = $data->convertAutoData();
    $this->apiKey = $paydata['consumer_key'];
    $this->secret = $paydata['consumer_secret'];
    $this->baseUrl = $paydata['base_url'];
    }
    public function getToken()
    {
         $url = $this->baseUrl . '/v3/api/Auth/RequestToken';
        $consumer_key = $this->apiKey;
        $consumer_secret = $this->secret;
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post($url, [
            'consumer_key' => $consumer_key,
            'consumer_secret' => $consumer_secret,
        ]);
        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json([
                'error' => 'Failed to authenticate with Pesapal',
                'details' => $response->body()
            ], $response->status());
        }
    }
    public function generatePaymentLink($amount, $currency, $description, $redirectUrl = null, $input)
{
    $tokenResponse = $this->getToken();
    if ($tokenResponse instanceof \Illuminate\Http\JsonResponse) {
        $error = $tokenResponse->getData(true); // convert to array
        return ['error' => 'Failed to get access token', 'details' => $error];
    }
    $accessToken = $tokenResponse['token']; // ✅ safe to access now
    $payload = [
        "id" => uniqid(),
        "currency" => $currency,
        "amount" => $amount,
        "description" => $description,
        "callback_url" => route('front.pesapal.notify'),
        "notification_id" => "3665c212-268d-47ec-ad98-db903e10c760",
        "billing_address" => [
            "email_address" => $input['customer_email'],
            "phone_number" => $input['customer_phone'],
            "country_code" => "TZ",
            "first_name" => $input['customer_name'],
            "last_name" => 'doe',
            "middle_name" => '',
            "line_1" => $input['customer_address'],
            "line_2" => "",
            "city" => $input['customer_city'],
            "state" => "",
            "postal_code" => ""
        ]
    ];
    $response = Http::withToken($accessToken)
        ->acceptJson()
        ->post($this->baseUrl . '/v3/api/Transactions/SubmitOrderRequest', $payload);
    if ($response->successful()) {
        Log::info('Pesapal SUCCESS:', $response->json());
        return $response->json();
    } else {
        Log::error('Pesapal ERROR:', $response->json());
        return [
            'error' => 'Payment initiation failed.',
            'details' => $response->json()
        ];
    }
}
    public function generatePaymentLinkDeposit($amount, $currency, $description, $redirectUrl = null, $user)
    {
        $token = $this->getToken();
        if (!$token) {
            return ['error' => 'Failed to get access token'];
        }
        $accessToken = $token['token'];
        $payload = [
            "id" => uniqid(), // Unique internal order ID
            "currency" => $currency, // Or USD, TZS, UGX
            "amount" => $amount, // Example amount
            "description" => $description,
            "callback_url" => route('deposit.pesapal.notify'),
            "notification_id" => "3665c212-268d-47ec-ad98-db903e10c760",
            "billing_address" => [
                "email_address" => $user->email,
                "phone_number" => $user->phone,
                "country_code" => "TZ",
                "first_name" => $user->name,
                "last_name" => 'doe',
                "middle_name" => '',
                "line_1" => $user->address,
                "line_2" => "",
                "city" => $user->city,
                "state" => "",
                "postal_code" => ""
            ]
        ];
        $response = Http::withToken($accessToken)
        ->acceptJson()
        ->post($this->baseUrl . '/v3/api/Transactions/SubmitOrderRequest', $payload);
        if ($response->successful()) {
        $responseData = $response->json();
        return $responseData;
    } else {
        return response()->json([
            'error' => 'Payment initiation failed.',
            'details' => $response->body()
        ], $response->status());
    }
    }
    public function requestPayment($amount, $phone, $firstName, $email, $description, $currency = 'XAF', $externalReference = null)
    {
        $token = $this->getToken();
        $response = Http::withHeaders([
            'Authorization' => 'Token ' . $token,
            'Content-Type' => 'application/json'
        ])->post("{$this->baseUrl}/collect/", [
            'amount' => '100',
            'from' => $phone,
            'first_name' => $firstName,
            'email' => $email,
            'description' => $description,
            'external_reference' => '',
        ]);
        dd($response->json());
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

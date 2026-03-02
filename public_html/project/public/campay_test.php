<?php

// Campay API credentials
$username = "AKsPMKEpaNkw5P8pMY_1ZbZa2f5GIjDeGHk36EDYqy27jUbqjsyeZca3WQzCyZHQ02JuLwIl_PQ_trq3-gmQcw";
$password = "nIT0UaG0paUCD2DYFjUxuVo7QjVkYcE-d80qXIESYnzUfGw...";

// Sandbox or live URL
$apiUrl = "https://sandbox-api.campay.com/v1/payment-links";

// Payment data
$data = [
    "amount" => 1000,
    "currency" => "USD",
    "description" => "Test payment",
    "external_reference" => "ORDER_TEST_123",
    "callback_url" => "https://yourwebsite.com/campay/notify",
    "failure_redirect_url" => "https://yourwebsite.com/payment/cancel"
];

// Use cURL
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password"); // Basic Auth
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP code: $httpcode\n";
echo "Response: $response\n";

<?php

return [
    'consumer_key'    => env('PESAPAL_CONSUMER_KEY'),
    'consumer_secret' => env('PESAPAL_CONSUMER_SECRET'),
    'base_url'        => env('PESAPAL_BASE_URL', 'https://pay.pesapal.com/v3'), // use sandbox url
];

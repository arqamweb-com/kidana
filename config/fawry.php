<?php

return [
    'merchant_code' => env('FAWRY_MERCHANT_CODE'),
    'secure_key' => env('FAWRY_SECURE_KEY'),
    'endpoint' => env('FAWRY_ENDPOINT', 'https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/charge'),
    'payment_method' => env('FAWRY_PAYMENT_METHOD', 'PayAtFawry'),
    'currency' => env('FAWRY_CURRENCY', 'EGP'),
    'expiry_minutes' => (int) env('FAWRY_EXPIRY_MINUTES', 1440),
];

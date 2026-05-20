<?php

return [
    'merchant_code' => env('FAWRY_MERCHANT_CODE'),
    'secure_key' => env('FAWRY_SECRET_KEY') ?: env('FAWRY_SECURE_KEY'),
    'mode' => env('FAWRY_MODE', 'sandbox'),

    'payment_method' => 'CARD',
    'status_endpoint' => env('FAWRY_STATUS_ENDPOINT', 'https://atfawry.fawrystaging.com/ECommerceWeb/Fawry/payments/status/v2'),

    'sandbox_js' => 'https://atfawry.fawrystaging.com/atfawry/plugin/assets/payments/js/fawrypay-payments.js',
    'sandbox_css' => 'https://atfawry.fawrystaging.com/atfawry/plugin/assets/payments/css/fawrypay-payments.css',

    'production_js' => 'https://atfawry.com/atfawry/plugin/assets/payments/js/fawrypay-payments.js',
    'production_css' => 'https://atfawry.com/atfawry/plugin/assets/payments/css/fawrypay-payments.css',
];

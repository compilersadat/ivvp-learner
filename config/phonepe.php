<?php

return [
    'env' => env('PHONEPE_ENV', 'sandbox'),
    'client_id' => env('PHONE_PE_MERCHANTID_PROD'),
    'client_secret' => env('PHONE_PE_SALT_KEY_PROD'),
    'client_version' => env('PHONEPE_CLIENT_VERSION', 1),
    'token_safety' => (int) env('PHONEPE_TOKEN_SAFETY', 60),

    // Base URLs per docs
    'base_urls' => [
        'sandbox' => [
            'oauth'    => 'https://api-preprod.phonepe.com/apis/pg-sandbox/v1/oauth/token', // POST form-encoded
            'checkout' => 'https://api-preprod.phonepe.com/apis/pg-sandbox/checkout/v2',
            'payments' => 'https://api-preprod.phonepe.com/apis/pg-sandbox/payments/v2',
        ],
        'production' => [
            'oauth'    => 'https://api.phonepe.com/apis/identity-manager/v1/oauth/token', // POST form-encoded
            'checkout' => 'https://api.phonepe.com/apis/pg/checkout/v2',
            'payments' => 'https://api.phonepe.com/apis/pg/payments/v2',
        ],
    ],
];
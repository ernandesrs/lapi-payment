<?php

return [
    'testing' => env('PAYMENT_TESTING'),
    'gateway' => env('PAYMENT_DEFAULT_GATEWAY'),

    /**
     * Gateways
     */

    'pagarme' => [
        'test_api_key' => env('PAYMENT_GATEWAY_PAGARME_API_TEST'),
        'live_api_key' => env('PAYMENT_GATEWAY_PAGARME_API_TEST'),
        'anti_fraud' => env('PAYMENT_GATEWAY_PAGARME_API_ANTIFRAUD')
    ]
];
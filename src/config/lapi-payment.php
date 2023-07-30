<?php

return [
    'testing' => env('PAYMENT_TESTING'),
    'gateway' => env('PAYMENT_DEFAULT_GATEWAY'),
    'default_installments' => 1,
    'allowed_min_installments' => 1,
    'allowed_max_installments' => 10,

    /**
     * Gateways
     */

    'pagarme' => [
        'test_api_key' => env('PAYMENT_GATEWAY_PAGARME_API_TEST'),
        'live_api_key' => env('PAYMENT_GATEWAY_PAGARME_API_TEST'),
        'anti_fraud' => env('PAYMENT_GATEWAY_PAGARME_API_ANTIFRAUD')
    ]
];
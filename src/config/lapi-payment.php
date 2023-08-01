<?php

return [
    /**
     * 
     * Testing payment
     * 
     */
    'testing' => env('PAYMENT_TESTING'),

    /**
     * 
     * Postback url for local tests using tools like:
     * https://requestbin.com/
     * https://ngrok.com/
     * 
     * When null or empty, will use the route route('lapi-payment.postback')
     * 
     */
    'postback_url_test' => empty(env('PAYMENT_POSTBACK_URL_LOCAL_TEST')) ? null : env('PAYMENT_POSTBACK_URL_LOCAL_TEST'),

    /**
     * 
     * The default gateway
     * See the implemented gateways:
     * https://github.com/ernandesrs/lapi-payment
     * 
     */
    'gateway' => env('PAYMENT_DEFAULT_GATEWAY'),

    /**
     * 
     * Initial installments
     * 
     */
    'default_installments' => 1,

    /**
     * 
     * Minimum number of installments
     * 
     */
    'allowed_min_installments' => 1,

    /**
     * 
     * Maximum number of installments
     * 
     */
    'allowed_max_installments' => 10,

    /**
     * 
     * * * * * * * * * * * * * * * * * * * * * *
     * GATEWAYS CONFIGURATIONS
     * * * * * * * * * * * * * * * * * * * * * *
     * 
     */

    /**
     * 
     * Pagarme
     * 
     */
    'pagarme' => [
        'test_api_key' => env('PAYMENT_GATEWAY_PAGARME_API_TEST'),
        'live_api_key' => env('PAYMENT_GATEWAY_PAGARME_API_TEST'),
        'anti_fraud' => env('PAYMENT_GATEWAY_PAGARME_API_ANTIFRAUD')
    ]
];
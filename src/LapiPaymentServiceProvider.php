<?php

namespace Ernandesrs\LapiPayment;

use Illuminate\Support\ServiceProvider;

class LapiPaymentServiceProvider extends ServiceProvider
{
    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        // 
    }

    /**
     * Boot
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/lapi-payment.php' => config_path('lapi-payment.php'),
        ], 'lapi-payment-config');
    }
}
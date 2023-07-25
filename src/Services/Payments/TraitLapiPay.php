<?php

namespace Ernandesrs\LapiPayment\Services\Payments;

trait TraitLapiPay
{
    /**
     * Add customer
     *
     * @param \App\Models\User $user
     * @return LapiPay
     */
    public function addCustomer(\App\Models\User $user)
    {
        $this->gatewayInstance->addCustomer($user);
        return $this;
    }

    /**
     * Gateway
     *
     * @param string $gateway
     * @return LapiPay
     */
    public function gateway(string $gateway)
    {
        try {
            $this->gatewayInstance = new ($this->gateways[$gateway]);
        } catch (\Exception $e) {
            throw new \Exception('"' . $gateway . '" is a invalid gateway');
        }

        return $this;
    }
}
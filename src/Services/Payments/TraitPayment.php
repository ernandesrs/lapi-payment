<?php

namespace Ernandesrs\LapiPayment\Services\Payments;

trait TraitPayment
{
    /**
     * Add customer
     *
     * @param \App\Models\User $user
     * @return Payment
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
     * @return Payment
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
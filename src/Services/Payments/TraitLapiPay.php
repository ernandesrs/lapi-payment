<?php

namespace Ernandesrs\LapiPayment\Services\Payments;

trait TraitLapiPay
{
    /**
     * Add customer
     *
     * @param \App\Models\User $customer
     * @return LapiPay
     */
    public function addCustomer(\App\Models\User $customer)
    {
        $this->gatewayInstance->addCustomer($customer);
        return $this;
    }

    /**
     * Add a billingg
     *
     * @param \App\Models\User $customer
     * @return LapiPay
     */
    public function addBilling(\App\Models\User $customer)
    {
        $this->gatewayInstance->addBilling($customer);
        return $this;
    }

    /**
     * Add product
     *
     * @param string $id
     * @param string $title
     * @param string $unitPrice
     * @param string $quantity
     * @param boolean $isTangible
     * @return LapiPay
     */
    public function addProduct(string $id, string $title, string $unitPrice, string $quantity, bool $isTangible)
    {
        $this->gatewayInstance->addProduct($id, $title, $unitPrice, $quantity, $isTangible);
        return $this;
    }

    /**
     * Get error messages
     *
     * @return null|array
     */
    public function errorMessages(): ?array
    {
        return \Ernandesrs\LapiPayment\Services\Validator::errorMessages();
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
            $this->gatewayInstance = new($this->gateways[$gateway]);
        } catch (\Exception $e) {
            throw new \Exception('"' . $gateway . '" is a invalid gateway');
        }

        return $this;
    }
}
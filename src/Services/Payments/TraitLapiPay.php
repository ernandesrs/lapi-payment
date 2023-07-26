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
     * Add a billingg
     *
     * @param string $name
     * @param string $street
     * @param string $streetNumber
     * @param string $zipcode
     * @param string $country
     * @param string $state
     * @param string $city
     * @param string $neighborhood
     * @param string $complementary
     * @return LapiPay
     */
    public function addBilling(string $name, string $street, string $streetNumber, string $zipcode, string $country, string $state, string $city, string $neighborhood, string $complementary)
    {
        $this->gatewayInstance->addBilling($name, $street, $streetNumber, $zipcode, $country, $state, $city, $neighborhood, $complementary);
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
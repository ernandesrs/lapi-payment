<?php

namespace Ernandesrs\LapiPayment\Facades;

use Illuminate\Support\Facades\Facade;
use Ernandesrs\LapiPayment\Services\Payments\LapiPay as LapiPayService;

class LapiPay extends Facade
{
    /**
     * Validate a card
     *
     * @param string $holderName
     * @param string $number
     * @param string $cvv
     * @param string $expiration
     * @return null|\Ernandesrs\LapiPayment\Models\Card
     */
    public static function createCard(string $holderName, string $number, string $cvv, string $expiration)
    {
        return (new LapiPayService())->createCard($holderName, $number, $cvv, $expiration);
    }

    /**
     * Charge with credit card
     *
     * @param string $cardHash
     * @param float $amount
     * @param integer $installments
     * @param array $metadata
     * @return null|\Ernandesrs\LapiPayment\Models\Payment
     */
    public static function chargeWithCard(string $cardHash, float $amount, int $installments, array $metadata = [])
    {
        return (new LapiPayService())->chargeWithCard($cardHash, $amount, $installments, $metadata);
    }

    /**
     * Add a customer
     *
     * @param \App\Models\User $user
     * @return \Ernandesrs\LapiPayment\Services\Payments\LapiPay
     */
    public static function addCustomer(\App\Models\User $user)
    {
        return (new LapiPayService())->addCustomer($user);
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
     * @return \Ernandesrs\LapiPayment\Services\Payments\LapiPay
     */
    public function addBilling(string $name, string $street, string $streetNumber, string $zipcode, string $country, string $state, string $city, string $neighborhood, string $complementary)
    {
        return (new LapiPayService())->addBilling($name, $street, $streetNumber, $zipcode, $country, $state, $city, $neighborhood, $complementary);
    }

    /**
     * Add product
     *
     * @param string $id
     * @param string $title
     * @param string $unitPrice
     * @param string $quantity
     * @param boolean $isTangible
     * @return \Ernandesrs\LapiPayment\Services\Payments\LapiPay
     */
    public static function addProduct(string $id, string $title, string $unitPrice, string $quantity, bool $isTangible)
    {
        return (new LapiPayService())->addProduct($id, $title, $unitPrice, $quantity, $isTangible);
    }
}
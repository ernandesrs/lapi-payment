<?php

namespace Ernandesrs\LapiPayment\Facades;

use Illuminate\Support\Facades\Facade;
use Ernandesrs\LapiPayment\Services\Payments\LapiPay as LapiPayService;

class LapiPay extends Facade
{
    /**
     * Validate and save a card
     *
     * @param \App\Models\User $user
     * @param string $holderName
     * @param string $number
     * @param string $cvv
     * @param string $expiration
     * @return null|\Ernandesrs\LapiPayment\Models\Card
     */
    public static function createCard(\App\Models\User $user, string $holderName, string $number, string $cvv, string $expiration)
    {
        return (new LapiPayService())->createCard($user, $holderName, $number, $cvv, $expiration);
    }

    /**
     * Charge with credit card
     *
     * @param \Ernandesrs\LapiPayment\Models\Card $card
     * @param float $amount
     * @param integer $installments
     * @param array $metadata
     * @return null|\Ernandesrs\LapiPayment\Models\Payment
     */
    public static function chargeWithCard(\Ernandesrs\LapiPayment\Models\Card $card, float $amount, int $installments, array $metadata = [])
    {
        return (new LapiPayService())->chargeWithCard($card, $amount, $installments, $metadata);
    }

    /**
     * Add a customer
     *
     * @param \App\Models\User $customer
     * @return \Ernandesrs\LapiPayment\Services\Payments\LapiPay
     */
    public static function addCustomer(\App\Models\User $customer)
    {
        return (new LapiPayService())->addCustomer($customer);
    }

    /**
     * Add a billingg
     *
     * @param \App\Models\User $customer $customer
     * @return \Ernandesrs\LapiPayment\Services\Payments\LapiPay
     */
    public function addBilling(\App\Models\User $customer)
    {
        return (new LapiPayService())->addBilling($customer);
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
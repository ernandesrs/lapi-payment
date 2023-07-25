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
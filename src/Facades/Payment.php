<?php

namespace Ernandesrs\LapiPayment\Facades;

use Illuminate\Support\Facades\Facade;
use Ernandesrs\LapiPayment\Services\Payments\Payment as PaymentService;

class Payment extends Facade
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
        return (new PaymentService())->createCard($holderName, $number, $cvv, $expiration);
    }

    /**
     * Charge with credit card
     *
     * @param string $cardHash
     * @param float $amount
     * @param integer $installments
     * @param array $metadata
     * @return null|\ArrayObject
     */
    public static function chargeWithCard(string $cardHash, float $amount, int $installments, array $metadata = [])
    {
        return (new PaymentService())->chargeWithCard($cardHash, $amount, $installments, $metadata);
    }
}
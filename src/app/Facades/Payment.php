<?php

namespace ErnandesRS\LapiPayment\App\Facades;

use Illuminate\Support\Facades\Facade;
use ErnandesRS\LapiPayment\App\Services\Payments\Payment as PaymentService;

class Payment extends Facade
{
    /**
     * Validate a card
     *
     * @param string $holderName
     * @param string $number
     * @param string $cvv
     * @param string $expiration
     * @return \stdClass
     */
    public static function createCard(string $holderName, string $number, string $cvv, string $expiration)
    {
        return (new PaymentService())->createCard($holderName, $number, $cvv, $expiration);
    }
}
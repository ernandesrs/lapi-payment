<?php

namespace Ernandesrs\Test;

class CardController
{
    public function create($holderName, $number, $cvv, $expiration)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = \Auth::user();

        try {
            $card = \Ernandesrs\LapiPayment\Facades\LapiPay::createCard($user, $holderName, $number, $cvv, $expiration);

            echo "<h1>Created!</h1>";
            var_dump($card);
        } catch (\Ernandesrs\LapiPayment\Exceptions\InvalidDataException $e) {
            echo "<h1>Fail!</h1>";
            var_dump(\Ernandesrs\LapiPayment\Facades\LapiPay::errorMessages());
        } catch (\Ernandesrs\LapiPayment\Exceptions\InvalidCardException $e) {
            echo "<h1>Fail: Invalid card!</h1>";
        }
    }
}
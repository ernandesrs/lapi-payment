<?php

namespace Ernandesrs\Test;

class Test
{
    public function __construct()
    {
        // card creation
        $card = \Ernandesrs\LapiPayment\Facades\Payment::createCard('The Holder Name', '4916626701217934', '156', '0424');
        // var_dump($card);

        // cobrança com cartão
        // $chargeWithCard = \Ernandesrs\LapiPayment\Facades\Payment::chargeWithCard($card->hash, 101.98, 1, [
        //     'pack' => 'test'
        // ]);
        // var_dump($chargeWithCard);

        // cobrança com cartão adicionando dados do cliente
        $chargeWithCard = \Ernandesrs\LapiPayment\Facades\Payment::addCustomer(\Auth::user())->chargeWithCard($card->hash, 101.98, 1, ['pack' => 'test']);
        var_dump($chargeWithCard);
    }
}
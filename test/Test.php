<?php

namespace Ernandesrs\Test;

class Test
{
    public function __construct()
    {
        // card creation
        $card = \Ernandesrs\LapiPayment\Facades\LapiPay::createCard('The Holder Name', '4916626701217934', '156', '0424');
        // var_dump($card);

        // cobrança com cartão
        // $chargeWithCard = \Ernandesrs\LapiPayment\Facades\LapiPay::chargeWithCard($card->hash, 101.98, 1, [
        //     'pack' => 'test'
        // ]);
        // var_dump($chargeWithCard);

        // cobrança com cartão adicionando dados do cliente
        // $chargeWithCard = \Ernandesrs\LapiPayment\Facades\LapiPay::addCustomer(\Auth::user())->chargeWithCard($card->hash, 101.98, 1, ['pack' => 'test']);
        // var_dump($chargeWithCard);

        // adicionando produtos/items
        $lapipay = \Ernandesrs\LapiPayment\Facades\LapiPay::addProduct(2109, 'Curso Digital', 99.00, 1, false);
        $lapipay->addProduct(19203, 'Notebook Gaming 3i', 3985.94, 1, true);
        $lapipay->addProduct(9303, 'Celular Top de Linha', 2985.94, 1, true);
        $chargeWithcard = $lapipay->addCustomer(\Auth::user())->chargeWithCard($card->hash, 3985.94 + 99.00 + 2985.94, 1);

        var_dump($chargeWithcard);
    }
}
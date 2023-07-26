<?php

namespace Ernandesrs\Test;

class Test
{
    public function __construct()
    {
        // validar o cartão
        $card = \Ernandesrs\LapiPayment\Facades\LapiPay::createCard('The Holder Name', '4916626701217934', '156', '0424');

        // get customer
        $customer = \App\Models\User::where("id", 1)->first();

        $amount = 99.00 + 102.97 + 12.97;
        $installments = 1;
        $metadata = [
            'extra' => 'data'
        ];

        $lapipay = \Ernandesrs\LapiPayment\Facades\LapiPay::addCustomer($customer)
            ->addBilling($customer)
            ->addProduct(2109, 'Produto Digital', 99.00, 1, false)
            ->addProduct(9231, 'Produto Físico', 102.97, 1, true)
            ->addProduct(9231, 'Outro Produto', 12.97, 1, false)
            ->chargeWithCard($card, $amount, $installments, $metadata);
        
        var_dump($lapipay);
    }
}
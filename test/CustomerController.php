<?php

namespace Ernandesrs\Test;

use Ernandesrs\LapiPayment\Facades\LapiPay;

class CustomerController
{
    public function create()
    {
        $user = \App\Models\User::where("id", 2)->first();

        $customer = LapiPay::createCustomer($user);

        var_dump($customer->details());
    }
}
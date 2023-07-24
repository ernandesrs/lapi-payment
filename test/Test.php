<?php

namespace ErnandesRS\Test;

use \ErnandesRS\LapiPayment\App\Facades\Payment as PaymentFacade;

class Test
{
    public function __construct()
    {
        // card creation
        var_dump(PaymentFacade::createCard('The Holder Name', '4916626701217934', '156', '0424'));
    }
}
<?php

namespace Ernandesrs\Test;

class Test
{
    public function __construct()
    {
        // (new CardController)->create('The Holder Name', '4916626701217934', '156', '0424');

        (new CartController())->finalize(1);
    }
}
<?php

namespace Ernandesrs\LapiPayment\Exceptions;

class ChargedbackPaymentException extends \Exception
{
    /**
     * @var string
     */
    protected $message = "Payment was charged back";
}
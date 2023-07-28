<?php

namespace Ernandesrs\LapiPayment\Exceptions;

class RefusedPaymentException extends \Exception
{
    /**
     * @var string
     */
    protected $message = "Refused payment";
}
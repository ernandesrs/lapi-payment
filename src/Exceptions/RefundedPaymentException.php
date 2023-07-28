<?php

namespace Ernandesrs\LapiPayment\Exceptions;

class RefundedPaymentException extends \Exception
{
    /**
     * @var string
     */
    protected $message = "Refunded payment";
}
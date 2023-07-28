<?php

namespace Ernandesrs\LapiPayment\Exceptions;

class PaymentHasAlreadyBeenRefundedException extends \Exception
{
    /**
     * @var string
     */
    protected $message = "The payment has already been full refunded";
}
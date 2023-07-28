<?php

namespace Ernandesrs\LapiPayment\Exceptions;

use Exception;

class InvalidCardException extends Exception
{
    /**
     * @var string
     */
    protected $message = "The card provided is invalid";
}
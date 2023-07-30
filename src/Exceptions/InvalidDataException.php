<?php

namespace Ernandesrs\LapiPayment\Exceptions;

use Exception;

class InvalidDataException extends Exception
{
    /**
     * @var string
     */
    protected $message = "Invalid data found";

    /**
     * Constructor
     *
     * @param string $message
     * @param integer $code
     * @param [type] $previous
     */
    public function __construct($message = "", $code = 400, $previous = null)
    {
        $message = $message == null || strlen($message) == 0 ? $this->message : $message;
        parent::__construct($message, $code, $previous);
    }
}
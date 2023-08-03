<?php

namespace Ernandesrs\LapiPayment\Models;

class Phone
{
    public function __construct(
        public int $countryCode,
        public int $number
    ) {
    }

    /**
     * Full number
     *
     * @return string
     */
    public function full(): string
    {
        return '+' . $this->countryCode . '' . $this->number;
    }
}
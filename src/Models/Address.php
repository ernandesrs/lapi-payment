<?php

namespace Ernandesrs\LapiPayment\Models;

class Address
{
    public function __construct(
        public string $street,
        public string $streetNumber,
        public string $zipcode,
        public string $country,
        public string $state,
        public string $city,
        public string $neighborhood,
        public string $complementary,
    )
    {
    }
}
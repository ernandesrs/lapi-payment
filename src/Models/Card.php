<?php

namespace Ernandesrs\LapiPayment\Models;

class Card
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hash',
        'holder_name',
        'last_digits',
        'expiration_date',
        'country',
        'gateway'
    ];

    /**
     * Constructor
     *
     * @param string $hash
     * @param string $holder_name
     * @param string $last_digits
     * @param string $expiration_date
     * @param string $country
     * @param string $gateway
     */
    public function __construct(
        public string $hash,
        public string $holder_name,
        public string $last_digits,
        public string $expiration_date,
        public string $country,
        public string $gateway
    ) {
    }
}
<?php

namespace Ernandesrs\LapiPayment\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
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
     * @return Card
     */
    public function construct(
        string $hash,
        string $holder_name,
        string $last_digits,
        string $expiration_date,
        string $country,
        string $gateway
    ) {
        $this->hash = $hash;
        $this->holder_name = $holder_name;
        $this->last_digits = $last_digits;
        $this->expiration_date = $expiration_date;
        $this->country = $country;
        $this->gateway = $gateway;
        return $this;
    }
}
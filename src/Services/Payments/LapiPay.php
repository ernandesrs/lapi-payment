<?php

namespace Ernandesrs\LapiPayment\Services\Payments;

use Ernandesrs\LapiPayment\Exceptions\InvalidCardException;
use Ernandesrs\LapiPayment\Models\Card;

class LapiPay
{
    use TraitLapiPay;

    /**
     * Gateways
     *
     * @var array
     */
    private $gateways = [
        'pagarme' => \Ernandesrs\LapiPayment\Services\Payments\Gateways\Pagarme::class
    ];

    /**
     * Gateway instance
     *
     * @var \Ernandesrs\LapiPayment\Services\Payments\Gateways\Pagarme
     */
    private $gatewayInstance;

    /**
     * Constructor
     *
     * @param string $gateway
     */
    public function __construct(?string $gateway = null)
    {
        $this->gateway($gateway ?? config('lapi-payment.gateway'));
    }

    /**
     * Validate and save a card
     *
     * @param \App\Models\User $user
     * @param string $holderName
     * @param string $number
     * @param string $cvv
     * @param string $expiration
     * @return null|Card
     * @exception InvalidCardException
     */
    public function createCard(\App\Models\User $user, string $holderName, string $number, string $cvv, string $expiration)
    {
        $response = $this->gatewayInstance->createCard($holderName, $number, $cvv, $expiration);

        if (!$response) {
            throw new InvalidCardException();
        }

        return $user->cards()->firstOrCreate([
            'hash' => $response->id,
            'holder_name' => $response->holder_name,
            'last_digits' => $response->last_digits,
            'brand' => $response->brand,
            'expiration_date' => $response->expiration_date,
            'country' => $response->country,
            'gateway' => config('lapi-payment.gateway'),
            'valid' => $response->valid
        ]);
    }

    /**
     * Charge with credit card
     *
     * @param \Ernandesrs\LapiPayment\Models\Card $card
     * @param float $amount
     * @param integer $installments
     * @param array $metadata
     * @return null|\Ernandesrs\LapiPayment\Models\Payment
     */
    public function chargeWithCard(\Ernandesrs\LapiPayment\Models\Card $card, float $amount, int $installments, array $metadata = [])
    {
        return $this->gatewayInstance->chargeWithCard($card, $amount, $installments, $metadata);
    }
}
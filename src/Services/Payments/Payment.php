<?php

namespace Ernandesrs\LapiPayment\Services\Payments;

class Payment
{
    use TraitPayment;

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
     * Validate a card
     *
     * @param string $holderName
     * @param string $number
     * @param string $cvv
     * @param string $expiration
     * @return null|\Ernandesrs\LapiPayment\Models\Card
     */
    public function createCard(string $holderName, string $number, string $cvv, string $expiration)
    {
        return $this->gatewayInstance->createCard($holderName, $number, $cvv, $expiration);
    }

    /**
     * Charge with credit card
     *
     * @param string $cardHash
     * @param float $amount
     * @param integer $installments
     * @param array $metadata
     * @return null|\Ernandesrs\LapiPayment\Models\Payment
     */
    public function chargeWithCard(string $cardHash, float $amount, int $installments, array $metadata = [])
    {
        return $this->gatewayInstance->chargeWithCard($cardHash, $amount, $installments, $metadata);
    }
}
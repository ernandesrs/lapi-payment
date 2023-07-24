<?php

namespace ErnandesRS\LapiPayment\App\Services\Payments;

class Payment
{
    /**
     * Gateways
     *
     * @var array
     */
    private $gateways = [
        'pagarme' => \ErnandesRS\LapiPayment\App\Services\Payments\Gateways\Pagarme::class
    ];

    /**
     * Gateway
     *
     * @var string
     */
    private $gateway;

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
     * @return mixed
     */
    public function createCard(string $holderName, string $number, string $cvv, string $expiration)
    {
        return (new $this->gateway)->createCard($holderName, $number, $cvv, $expiration);
    }

    /**
     * Gateway
     *
     * @param string $gateway
     * @return Payment
     */
    public function gateway(string $gateway)
    {
        try {
            $this->gateway = $this->gateways[$gateway];
        } catch (\Exception $e) {
            throw new \Exception('"' . $gateway . '" is a invalid gateway');
        }

        return $this;
    }
}
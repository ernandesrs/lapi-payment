<?php

namespace ErnandesRS\LapiPayment\App\Services\Payments\Gateways;

class Pagarme
{
    /**
     * Pagarme instance
     *
     * @var \PagarMe\Client
     */
    private $pagarme;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagarme = new \PagarMe\Client($this->apiKey());
    }

    /**
     * Create card
     *
     * @param string $holderName
     * @param string $number
     * @param string $cvv
     * @param string $expiration
     * @return null|\stdClass
     */
    public function createCard(string $holderName, string $number, string $cvv, string $expiration)
    {
        $card = $this->pagarme->cards()->create([
            'card_holder_name' => $holderName,
            'card_number' => $number,
            'card_cvv' => $cvv,
            'card_expiration_date' => $expiration
        ]);

        if (!$card->valid) {
            return null;
        }

        return (object) [
            'gateway_card_hash' => $card->id,
            'valid' => $card->valid,
            'brand' => $card->brand,
            'holder_name' => $card->holder_name,
            'last_digits' => $card->last_digits,
            'first_digits' => $card->first_digits,
            'expiration_date' => $card->expiration_date,
            'date_created' => $card->date_created,
            'date_updated' => $card->date_updated,
            'country' => $card->country
        ];
    }

    public function chargeWithCard()
    {
        // 
    }

    public function chargeWithBankSlip()
    {
        // 
    }

    public function charge()
    {
        // 
    }

    /**
     * Get api key
     *
     * @return string
     */
    private function apiKey()
    {
        return config('lapi-payment.testing') === true ?
            config('lapi-payment.pagarme.test_api_key') :
            config('lapi-payment.pagarme.live_api_key');
    }
}
<?php

namespace Ernandesrs\LapiPayment\Services\Payments\Gateways;

use Ernandesrs\LapiPayment\Models\Card;

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
     * @return null|\Ernandesrs\LapiPayment\Models\Card
     */
    public function createCard(string $holderName, string $number, string $cvv, string $expiration)
    {
        $card = $this->pagarme->cards()->create([
            'card_holder_name' => $holderName,
            'card_number' => $number,
            'card_cvv' => $cvv,
            'card_expiration_date' => $expiration
        ]);

        return !$card->valid ?
            null :
            new Card(
                $card->id,
                $card->holder_name,
                $card->last_digits,
                $card->expiration_date,
                $card->country,
                'pagarme'
            );
    }

    /**
     * Charge with credit card
     *
     * @param string $cardHash
     * @param float $amount
     * @param integer $installments
     * @param array $metadata
     * @return null|\ArrayObject
     */
    public function chargeWithCard(string $cardHash, float $amount, int $installments, array $metadata = [])
    {
        $transaction = $this->charge($cardHash, $amount, $installments, 'credit_card', $metadata);

        if (!$transaction?->id) {
            return null;
        }

        return (object) [
            'gateway_transaction_id' => $transaction->id,
            'gateway_status' => $transaction->status,
            'gateway_response' => $transaction
        ];
    }

    public function chargeWithBankSlip()
    {
        // 
    }

    /**
     * Charge
     *
     * @param string $cardHash
     * @param float $amount
     * @param integer $installments
     * @param string $method
     * @param array $metadata
     * @return \ArrayObject
     */
    public function charge(string $cardHash, float $amount, int $installments, string $method, array $metadata = [])
    {
        return $this->pagarme->transactions()->create([
            'card_id' => $cardHash,
            'installments' => $installments,
            'amount' => $amount * 100,
            'payment_method' => $method,
            'metadata' => $metadata
        ]);
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
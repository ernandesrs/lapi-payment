<?php

namespace Ernandesrs\LapiPayment\Services\Payments\Gateways;

use Ernandesrs\LapiPayment\Models\Card as CardModel;
use Ernandesrs\LapiPayment\Models\Payment as PaymentModel;

class Pagarme
{
    /**
     * Pagarme instance
     *
     * @var \PagarMe\Client
     */
    private $pagarme;

    /**
     * Customer
     *
     * @var array
     */
    private $customer;

    /**
     * Products
     *
     * @var array
     */
    private $products;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagarme = new \PagarMe\Client($this->apiKey());
        $this->products = [];
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

        return !$card?->valid ?
            null :
            (new CardModel())->construct(
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
     * @return null|\Ernandesrs\LapiPayment\Models\Payment
     */
    public function chargeWithCard(string $cardHash, float $amount, int $installments, array $metadata = [])
    {
        return $this->charge($cardHash, $amount, $installments, 'credit_card', $metadata);
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
     * @return null|\Ernandesrs\LapiPayment\Models\Payment
     */
    public function charge(string $cardHash, float $amount, int $installments, string $method, array $metadata = [])
    {
        $data = [
            'card_id' => $cardHash,
            'installments' => $installments,
            'amount' => $amount * 100,
            'payment_method' => $method,
            'metadata' => $metadata
        ];

        if ($this->customer?->external_id) {
            $data['customer'] = $this->customer;
        }

        if (count($this->products)) {
            $data['items'] = $this->products;
        }

        $transaction = $this->pagarme->transactions()->create($data);

        return !$transaction?->id ?
            null :
            (new PaymentModel())->construct(
                $transaction->id,
                'pagarme',
                $transaction->payment_method,
                $transaction->amount,
                $transaction->installments,
                $transaction->status
            );
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

    /**
     * Add a customer
     *
     * @param \App\Models\User $user
     * @return Pagarme
     */
    public function addCustomer(\App\Models\User $user)
    {
        $this->customer = (object) [
            'external_id' => $user->customerId(),
            'name' => $user->customerName(),
            'email' => $user->customerEmail(),
            'country' => $user->customerCountry(),
            'type' => $user->customerType(),
            'documents' => $user->customerDocuments(),
            'phone_numbers' => $user->customerPhoneNumbers()
        ];
        return $this;
    }

    /**
     * Add product
     *
     * @param string $id
     * @param string $title
     * @param string $unitPrice
     * @param string $quantity
     * @param boolean $isTangible true if the product is not a digital product
     * @return Pagarme
     */
    public function addProduct(string $id, string $title, string $unitPrice, string $quantity, bool $isTangible)
    {
        array_push($this->products, [
            'id' => $id,
            'title' => $title,
            'unit_price' => $unitPrice * 100,
            'quantity' => $quantity,
            'tangible' => $isTangible
        ]);
        return $this;
    }
}
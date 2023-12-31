<?php

namespace Ernandesrs\LapiPayment\Services\Payments\Gateways;

use Ernandesrs\LapiPayment\Models\Card as CardModel;
use Ernandesrs\LapiPayment\Models\Payment as PaymentModel;

class Pagarme
{
    use PagarmePostback;

    /**
     * Pagarme instance
     *
     * @var \PagarMe\Client
     */
    private $pagarme;

    /**
     * Customer
     *
     * @var object
     */
    private $customer;

    /**
     * Billing
     *
     * @var object
     */
    private $billing;

    /**
     * Products
     *
     * @var array
     */
    private $products;

    /**
     * Data
     * 
     * @var array
     */
    private $data;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagarme = new \PagarMe\Client($this->apiKey());
        $this->customer = (object) [];
        $this->billing = (object) [];
        $this->products = [];
    }

    /**
     * Create customer
     *
     * @param string $id
     * @param string $name
     * @param string $email
     * @param string $country
     * @param \Ernandesrs\LapiPayment\Models\Phone $phone
     * @param \Ernandesrs\LapiPayment\Models\Document $document
     * @param string $type individual/corporation
     * @return null|\ArrayObject
     */
    public function createCustomer(
        string $id,
        string $name,
        string $email,
        string $country,
        \Ernandesrs\LapiPayment\Models\Phone $phone,
        \Ernandesrs\LapiPayment\Models\Document $document,
        string $type = 'individual'
    ) {
        $customer = $this->pagarme->customers()->create([
            'external_id' => $id,
            'name' => $name,
            'type' => $type,
            'country' => $country,
            'email' => $email,
            'documents' => [
                [
                    'type' => $document->type,
                    'number' => $document->number . ''
                ]
            ],
            'phone_numbers' => [
                $phone->full()
            ],
        ]);

        return $customer->id ?? null ? $customer : null;
    }

    /**
     * Validate and create a card
     *
     * @param string $holderName
     * @param string $number
     * @param string $cvv
     * @param string $expiration
     * @return null|\ArrayObject
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
            $card;
    }

    /**
     * Charge with credit card
     *
     * @param \Ernandesrs\LapiPayment\Models\Card $card
     * @param float $amount
     * @param integer $installments
     * @param array $metadata
     * @return null|\ArrayObject
     */
    public function chargeWithCard(CardModel $card, float $amount, int $installments, array $metadata = [])
    {
        return $this->charge($card->hash, $amount, $installments, 'credit_card', $metadata);
    }

    /**
     * Charge
     *
     * @param string $cardHash
     * @param float $amount
     * @param integer $installments
     * @param string $method
     * @param array $metadata
     * @return null|\ArrayObject
     */
    public function charge(string $cardHash, float $amount, int $installments, string $method, array $metadata = [])
    {
        $this->data = [
            'card_id' => $cardHash,
            'installments' => $installments,
            'amount' => $amount * 100,
            'payment_method' => $method,
            'metadata' => $metadata,
            'postback_url' => config('lapi-payment.postback_url_test', null) ?? route('lapi-payment.postback')
        ];

        $this->antifraudFields($method);

        return $this->pagarme->transactions()->create($this->data);
    }

    /**
     * Refund a payment
     *
     * @param PaymentModel $payment the payment
     * @param float|null $amount amount to refund. Full refund when null.
     * @param array $metadata
     * @return null|\ArrayObject
     */
    public function refundPayment(PaymentModel $payment, ?float $amount = null, array $metadata = [])
    {
        $data = [
            'id' => $payment->transaction_id,
            'metadata' => $metadata
        ];

        if ($amount) {
            $data['amount'] = $amount * 100;
        }

        return $this->pagarme->transactions()->refund($data);
    }

    /**
     * Payment details
     *
     * @param string $transactionId
     * @return null|\ArrayObject
     */
    public function paymentDetails(string $transactionId)
    {
        $transaction = $this->pagarme->transactions()->get([
            'id' => $transactionId
        ]);

        return $transaction->id ?? null ? $transaction : null;
    }

    /**
     * Customer details
     *
     * @param string $customerId
     * @return null|\ArrayObject
     */
    public function customerDetails(string $customerId)
    {
        $customer = $this->pagarme->customers()->get([
            'id' => $customerId
        ]);

        return $customer->id ?? null ? $customer : null;
    }

    /**
     * Add a customer
     *
     * @param \App\Models\User $customer
     * @return Pagarme
     */
    public function addCustomer(\App\Models\User $customer)
    {
        $this->customer = (object) [
            'external_id' => $customer->customerId(),
            'name' => $customer->customerName(),
            'email' => $customer->customerEmail(),
            'country' => $customer->customerCountry(),
            'type' => $customer->customerType(),
            'documents' => $customer->customerDocuments(),
            'phone_numbers' => $customer->customerPhoneNumbers()
        ];
        return $this;
    }

    /**
     * Add a billing
     * 
     * @param \App\Models\User $customer
     * @return Pagarme
     */
    public function addBilling(\App\Models\User $customer)
    {
        $this->billing = (object) [
            'name' => $customer->customerName(),
            'address' => [
                'street' => $customer->customerAddress()->street,
                'street_number' => $customer->customerAddress()->streetNumber,
                'zipcode' => $customer->customerAddress()->zipcode,
                'country' => $customer->customerAddress()->country,
                'state' => $customer->customerAddress()->state,
                'city' => $customer->customerAddress()->city,
                'neighborhood' => $customer->customerAddress()->neighborhood,
                'complementary' => $customer->customerAddress()->complementary
            ]
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

    /**
     * Antifraud fields check and set
     *
     * @param string $method
     * @return void
     * @exception \Exception
     */
    private function antifraudFields(string $method)
    {
        if (
            $method == 'credit_card' &&
            config('lapi-payment.pagarme.anti_fraud') &&
            (
                !($this->customer->external_id ?? null) ||
                !($this->billing->name ?? null) ||
                count($this->products) < 1
            )
        ) {
            throw new \Exception('Need customer, billing and products when anti-fraud is enabled');
        }

        if ($this->customer?->external_id ?? null) {
            $this->data['customer'] = $this->customer;
        }

        if ($this->billing?->name ?? null) {
            $this->data['billing'] = $this->billing;
        }

        if (count($this->products)) {
            $this->data['items'] = $this->products;
        }
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
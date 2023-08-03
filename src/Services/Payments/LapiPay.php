<?php

namespace Ernandesrs\LapiPayment\Services\Payments;

use Ernandesrs\LapiPayment\Exceptions\InvalidCardException;
use Ernandesrs\LapiPayment\Exceptions\PaymentHasAlreadyBeenRefundedException;
use Ernandesrs\LapiPayment\Models\Card;
use Ernandesrs\LapiPayment\Models\Payment;
use Ernandesrs\LapiPayment\Models\UserIsCustomer;

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
     * Create customer
     *
     * @param \App\Models\User $user
     * @param ?string $id
     * @param ?string $name
     * @param ?string $email
     * @param ?string $country
     * @param ?\Ernandesrs\LapiPayment\Models\Phone $phone
     * @param ?\Ernandesrs\LapiPayment\Models\Document $document
     * @param ?string $type individual/corporation
     * @return null|\Ernandesrs\LapiPayment\Models\UserIsCustomer
     */
    public function createCustomer(
        \App\Models\User $user,
        ?string $id = null,
        ?string $name = null,
        ?string $email = null,
        ?string $country = null,
        ?\Ernandesrs\LapiPayment\Models\Phone $phone = null,
        ?\Ernandesrs\LapiPayment\Models\Document $document = null,
        ?string $type = null
    ) {
        if ($user->isCustomer()) {
            return $user->customer()->first();
        }

        $customer = $this->gatewayInstance->createCustomer(
            $id ?? $user->customerId(),
            $name ?? $user->customerName(),
            $email ?? $user->customerEmail(),
            $country ?? $user->customerCountry(),
            $phone ?? $user->customerPhone(),
            $document ?? $user->customerDocument(),
            $type ?? $user->customerType()
        );
        return !$customer ? throw new \Ernandesrs\LapiPayment\Exceptions\InvalidDataException() : $user->customer()->create([
            'gateway' => config('lapi-payment.gateway'),
            'customer_id' => $customer->id
        ]);
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
     * @throws \Ernandesrs\LapiPayment\Exceptions\InvalidCardException|\Ernandesrs\LapiPayment\Exceptions\InvalidDataException
     */
    public function createCard(\App\Models\User $user, string $holderName, string $number, string $cvv, string $expiration)
    {
        $validated = \Ernandesrs\LapiPayment\Services\Validator::validateCard($holderName, $number, $cvv, $expiration);

        $card = $this->gatewayInstance->createCard(
            $validated['holder_name'],
            $validated['number'],
            $validated['cvv'],
            $validated['expiration']
        );

        return !$card ? throw new InvalidCardException() : $user->cards()->firstOrCreate([
            'hash' => $card->id,
            'holder_name' => $card->holder_name,
            'last_digits' => $card->last_digits,
            'brand' => $card->brand,
            'expiration_date' => $card->expiration_date,
            'country' => $card->country,
            'gateway' => config('lapi-payment.gateway'),
            'valid' => $card->valid
        ]);
    }

    /**
     * Charge with credit card
     *
     * @param \App\Models\User $user
     * @param Card $card
     * @param float $amount
     * @param integer $installments
     * @param array $metadata
     * @return null|Payment
     * @throws \Ernandesrs\LapiPayment\Exceptions\InvalidCardException
     * @throws \Ernandesrs\LapiPayment\Exceptions\InvalidDataException
     * @throws \Ernandesrs\LapiPayment\Exceptions\ChargedbackPaymentException
     * @throws \Ernandesrs\LapiPayment\Exceptions\RefundedPaymentException
     * @throws \Ernandesrs\LapiPayment\Exceptions\RefusedPaymentException
     */
    public function chargeWithCard(\App\Models\User $user, Card $card, float $amount, int $installments, array $metadata = [])
    {
        $validated = \Ernandesrs\LapiPayment\Services\Validator::validateChargeData($amount, $installments);

        $transaction = $this->gatewayInstance->chargeWithCard($card, $validated['amount'], $validated['installments'], $metadata);

        if (in_array($transaction->status, ['refunded', 'refused', 'chargedback'])) {
            $exception = "\\Ernandesrs\\Exceptions\\" . ucfirst($transaction->status) . "PaymentException";
            throw new $exception;
        }

        return $user->payments()->create([
            'transaction_id' => $transaction->id,
            'card_id' => $card->id,
            'gateway' => config('lapi-payment.gateway'),
            'method' => $transaction->payment_method,
            'amount' => $amount,
            'installments' => $transaction->installments,
            'status' => $transaction->status
        ]);
    }

    /**
     * Refund payment
     *
     * @param Payment $payment
     * @param float|null $amount amount to refund. Full refund when null.
     * @param array $metadata
     * @return Payment
     * @throws \Ernandesrs\LapiPayment\Exceptions\PaymentHasAlreadyBeenRefundedException
     * @throws \Ernandesrs\LapiPayment\Exceptions\InvalidDataException
     */
    public function refundPayment(Payment $payment, ?float $amount = null, array $metadata = [])
    {
        if ($payment->status == 'refunded') {
            throw new PaymentHasAlreadyBeenRefundedException();
        }

        $validated = \Ernandesrs\LapiPayment\Services\Validator::validateRefundData($payment, $amount);

        $refund = $this->gatewayInstance->refundPayment($payment, $validated['amount'], $metadata);

        $payment->amount = $validated['amount'] && $validated['amount'] < $payment->amount ? ($payment->amount - $validated['amount']) : $payment->amount;
        $payment->status = $refund->status;
        $payment->save();

        return $payment;
    }

    /**
     * Get payment details registered by gateway
     *
     * @param Payment $payment
     * @return null|\ArrayObject
     */
    public function paymentDetails(Payment $payment)
    {
        return $this->gatewayInstance->paymentDetails($payment->transaction_id);
    }

    /**
     * Get customer details registered by gateway
     *
     * @param UserIsCustomer $payment
     * @return null|\ArrayObject
     */
    public function customerDetails(UserIsCustomer $customer)
    {
        return $this->gatewayInstance->customerDetails($customer->customer_id);
    }

    /**
     * Postback
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function postback(\Illuminate\Http\Request $request)
    {
        return $this->gatewayInstance->postback($request);
    }
}
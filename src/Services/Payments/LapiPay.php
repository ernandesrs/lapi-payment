<?php

namespace Ernandesrs\LapiPayment\Services\Payments;

use Ernandesrs\LapiPayment\Exceptions\InvalidCardException;
use Ernandesrs\LapiPayment\Exceptions\PaymentHasAlreadyBeenRefundedException;
use Ernandesrs\LapiPayment\Models\Card;
use Ernandesrs\LapiPayment\Models\Payment;

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
     */
    public function refundPayment(Payment $payment, ?float $amount = null, array $metadata = [])
    {
        if ($payment->status == 'refunded') {
            throw new PaymentHasAlreadyBeenRefundedException();
        }

        $refund = $this->gatewayInstance->refundPayment($payment, $amount, $metadata);

        $payment->amount = $amount && $amount < $payment->amount ? ($payment->amount - $amount) : $payment->amount;
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
}
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
     * @param \App\Models\User $user
     * @param Card $card
     * @param float $amount
     * @param integer $installments
     * @param array $metadata
     * @return null|Payment
     * @exception
     */
    public function chargeWithCard(\App\Models\User $user, Card $card, float $amount, int $installments, array $metadata = [])
    {
        $transaction = $this->gatewayInstance->chargeWithCard($card, $amount, $installments, $metadata);

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
     * @exception \Ernandesrs\LapiPayment\Exceptions\PaymentHasAlreadyBeenRefundedException
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
}
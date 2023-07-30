<?php

namespace Ernandesrs\LapiPayment\Facades;

use Illuminate\Support\Facades\Facade;
use Ernandesrs\LapiPayment\Services\Payments\LapiPay as LapiPayService;

class LapiPay extends Facade
{
    /**
     * Validate and save a card
     *
     * @param \App\Models\User $user
     * @param string $holderName
     * @param string $number
     * @param string $cvv
     * @param string $expiration
     * @return null|\Ernandesrs\LapiPayment\Models\Card
     * @throws \Ernandesrs\LapiPayment\Exceptions\InvalidCardException|\Ernandesrs\LapiPayment\Exceptions\InvalidDataException
     */
    public static function createCard(\App\Models\User $user, string $holderName, string $number, string $cvv, string $expiration)
    {
        return (new LapiPayService())->createCard($user, $holderName, $number, $cvv, $expiration);
    }

    /**
     * Charge with credit card
     *
     * @param \App\Models\User $user
     * @param \Ernandesrs\LapiPayment\Models\Card $card
     * @param float $amount
     * @param integer $installments
     * @param array $metadata
     * @return null|\Ernandesrs\LapiPayment\Models\Payment
     * @throws \Ernandesrs\LapiPayment\Exceptions\InvalidCardException
     * @throws \Ernandesrs\LapiPayment\Exceptions\InvalidDataException
     * @throws \Ernandesrs\LapiPayment\Exceptions\ChargedbackPaymentException
     * @throws \Ernandesrs\LapiPayment\Exceptions\RefundedPaymentException
     * @throws \Ernandesrs\LapiPayment\Exceptions\RefusedPaymentException
     */
    public static function chargeWithCard(\App\Models\User $user, \Ernandesrs\LapiPayment\Models\Card $card, float $amount, int $installments, array $metadata = [])
    {
        return (new LapiPayService())->chargeWithCard($user, $card, $amount, $installments, $metadata);
    }

    /**
     * Refund payment
     *
     * @param \Ernandesrs\LapiPayment\Models\Payment $payment
     * @param float|null $amount amount to refund. Full refund when null.
     * @param array $metadata
     * @return \Ernandesrs\LapiPayment\Models\Payment
     */
    public static function refundPayment(\Ernandesrs\LapiPayment\Models\Payment $payment, ?float $amount = null, array $metadata = [])
    {
        return (new LapiPayService())->refundPayment($payment, $amount, $metadata);
    }

    /**
     * Get payment details registered by configured gateway
     *
     * @param \Ernandesrs\LapiPayment\Models\Payment $payment
     * @return null|\ArrayObject
     */
    public static function paymentDetails(\Ernandesrs\LapiPayment\Models\Payment $payment)
    {
        return (new LapiPayService())->paymentDetails($payment);
    }

    /**
     * Add a customer
     *
     * @param \App\Models\User $customer
     * @return \Ernandesrs\LapiPayment\Services\Payments\LapiPay
     */
    public static function addCustomer(\App\Models\User $customer)
    {
        return (new LapiPayService())->addCustomer($customer);
    }

    /**
     * Add a billingg
     *
     * @param \App\Models\User $customer $customer
     * @return \Ernandesrs\LapiPayment\Services\Payments\LapiPay
     */
    public function addBilling(\App\Models\User $customer)
    {
        return (new LapiPayService())->addBilling($customer);
    }

    /**
     * Add product
     *
     * @param string $id
     * @param string $title
     * @param string $unitPrice
     * @param string $quantity
     * @param boolean $isTangible
     * @return \Ernandesrs\LapiPayment\Services\Payments\LapiPay
     */
    public static function addProduct(string $id, string $title, string $unitPrice, string $quantity, bool $isTangible)
    {
        return (new LapiPayService())->addProduct($id, $title, $unitPrice, $quantity, $isTangible);
    }

    /**
     * Get error messages
     *
     * @return array
     */
    public static function errorMessages()
    {
        return (new LapiPayService())->errorMessages();
    }
}
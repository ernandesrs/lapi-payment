<?php

namespace Ernandesrs\LapiPayment\Facades;

use Illuminate\Support\Facades\Facade;
use Ernandesrs\LapiPayment\Services\Payments\LapiPay as LapiPayService;

class LapiPay extends Facade
{
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
    public static function createCustomer(
        \App\Models\User $user,
        ?string $id = null,
        ?string $name = null,
        ?string $email = null,
        ?string $country = null,
        ?\Ernandesrs\LapiPayment\Models\Phone $phone = null,
        ?\Ernandesrs\LapiPayment\Models\Document $document = null,
        ?string $type = null
    ) {
        return (new LapiPayService())->createCustomer($user, $id, $name, $email, $country, $phone, $document, $type);
    }

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
     * @throws \Ernandesrs\LapiPayment\Exceptions\PaymentHasAlreadyBeenRefundedException
     * @throws \Ernandesrs\LapiPayment\Exceptions\InvalidDataException
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
     * Get customer details registered by configured gateway
     *
     * @param \Ernandesrs\LapiPayment\Models\UserIsCustomer $customer
     * @return null|\ArrayObject
     */
    public static function customerDetails(\Ernandesrs\LapiPayment\Models\UserIsCustomer $customer)
    {
        return (new LapiPayService())->customerDetails($customer);
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
     * @return null|array
     */
    public static function errorMessages(): ?array
    {
        return (new LapiPayService())->errorMessages();
    }
}
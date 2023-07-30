<?php

namespace Ernandesrs\LapiPayment\Services;

class Validator
{
    /**
     * Validate card
     *
     * @param string $holderName
     * @param string $number
     * @param string $cvv
     * @param string $expiration
     * @return array validated data
     * @throws \Ernandesrs\LapiPayment\Exceptions\InvalidDataException
     */
    public static function validateCard(string $holderName, string $number, string $cvv, string $expiration)
    {
        return self::validate([
            'holder_name' => $holderName,
            'number' => $number,
            'cvv' => $cvv,
            'expiration' => $expiration
        ], [
            'holder_name' => ['required'],
            'number' => ['required', 'numeric', 'digits:16'],
            'cvv' => ['required', 'numeric', 'digits:3'],
            'expiration' => ['required', 'numeric', 'digits:4']
        ], [
            'number' => __('lapi-payment-lang::lapi-payment.card.number'),
            'cvv' => __('lapi-payment-lang::lapi-payment.card.cvv'),
            'expiration' => __('lapi-payment-lang::lapi-payment.card.expiration')
        ]);
    }

    /**
     * Undocumented function
     *
     * @param float $amount
     * @param integer $installments
     * @return array validated data
     * @throws \Ernandesrs\LapiPayment\Exceptions\InvalidDataException
     */
    public static function validateChargeData(float $amount, int $installments)
    {
        return self::validate([
            'amount' => $amount,
            'installments' => $installments
        ], [
            'amount' => ['required', 'decimal:2'],
            'installments' => [
                'required',
                'integer',
                'between:' . config('lapi-payment.allowed_min_installments') . ',' . config('lapi-payment.allowed_max_installments')
            ]
        ], [
            'amount.decimal' => __('lapi-payment-lang::lapi-payment.charge.amount.decimal'),

            'installments.integer' => __('lapi-payment-lang::lapi-payment.charge.installments.integer'),
            'installments.between' => __(
                'lapi-payment-lang::lapi-payment.charge.installments.between',
                [
                    'min' => config('lapi-payment.allowed_min_installments'),
                    'max' => config('lapi-payment.allowed_max_installments')
                ]
            )
        ]);
    }

    /**
     * Validate
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return array validated data
     * @throws \Ernandesrs\LapiPayment\Exceptions\InvalidDataException
     */
    public static function validate(array $data, array $rules, array $messages)
    {
        $validator = \Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            \Session::flash('lapi_payment_validation_errors', $validator->errors()->getMessages());
            throw new \Ernandesrs\LapiPayment\Exceptions\InvalidDataException();
        }

        return $validator->validated();
    }

    /**
     * Error messages
     *
     * @return array
     */
    public static function errorMessages(): array
    {
        return \Session::get('lapi_payment_validation_errors');
    }
}
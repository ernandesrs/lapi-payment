<?php

namespace Ernandesrs\Test;

class CartController
{
    public function finalize($installments)
    {
        /**
         * @var \App\Models\User
         */
        $customer = \Auth::user();
        $card = $customer->cards()->first();

        if (!$card) {
            echo "<h1>No registered card</h1>";
            die;
        }

        try {
            $payment = \Ernandesrs\LapiPayment\Facades\LapiPay::addCustomer($customer)
                ->addBilling($customer)
                ->addProduct(2109, 'Produto Digital', 99.00, 1, false)
                ->chargeWithCard($customer, $card, 99.00, $installments);

            echo "<h1>Paid!</h1>";
            var_dump($payment);
        } catch (\Ernandesrs\LapiPayment\Exceptions\InvalidDataException $e) {
            echo "<h1>Fail!</h1>";
            var_dump(\Ernandesrs\LapiPayment\Facades\LapiPay::errorMessages());
        } catch (\Ernandesrs\LapiPayment\Exceptions\RefundedPaymentException $e) {
            echo "<h1>Fail: refunded payment!</h1>";
        } catch (\Ernandesrs\LapiPayment\Exceptions\RefusedPaymentException $e) {
            echo "<h1>Fail: refused payment!</h1>";
        } catch (\Ernandesrs\LapiPayment\Exceptions\InvalidCardException $e) {
            echo "<h1>Fail: invalid card!</h1>";
        }
    }
}
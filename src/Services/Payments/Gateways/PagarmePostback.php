<?php

namespace Ernandesrs\LapiPayment\Services\Payments\Gateways;

trait PagarmePostback
{
    /**
     * Postback
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function postback(\Illuminate\Http\Request $request)
    {
        /**
         * https://docs.pagar.me/v4/reference/postbacks
         */
        $postbackPayload = $request->all();

        if (!$this->pagarme->postbacks()->validate($request->getContent(), $request->header('X-Hub-Signature'))) {
            return;
        }

        switch ($postbackPayload['object']) {
            case 'transaction':
                $this->transactionPostback($postbackPayload['transaction']);
                break;
        }
    }

    /**
     * Transaction postback
     *
     * @param array $transaction
     * @return void
     */
    private function transactionPostback(array $transaction)
    {
        $payment = \Ernandesrs\LapiPayment\Models\Payment::where('transaction_id', $transaction['id'])->first();
        if (!$payment) {
            return;
        }

        $payment->status = $transaction['status'];
        $payment->save();
    }
}
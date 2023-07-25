<?php

namespace Ernandesrs\LapiPayment\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_id',
        'gateway',
        'method',
        'amount',
        'installments',
        'status'
    ];

    /**
     * Constructor
     *
     * @param string $transaction_id
     * @param string $gateway
     * @param string $method
     * @param string $amount
     * @param string $installments
     * @param string $status
     * @return Payment
     */
    public function construct(
        string $transaction_id,
        string $gateway,
        string $method,
        string $amount,
        string $installments,
        string $status
    ) {
        $this->transaction_id = $transaction_id;
        $this->gateway = $gateway;
        $this->method = $method;
        $this->amount = $amount;
        $this->installments = $installments;
        $this->status = $status;
        return $this;
    }
}
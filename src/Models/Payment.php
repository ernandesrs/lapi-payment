<?php

namespace Ernandesrs\LapiPayment\Models;

class Payment
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
     */
    public function __construct(
        public string $transaction_id,
        public string $gateway,
        public string $method,
        public string $amount,
        public string $installments,
        public string $status
    ) {
    }
}
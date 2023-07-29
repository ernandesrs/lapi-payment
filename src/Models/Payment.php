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
        'card_id',
        'gateway',
        'method',
        'amount',
        'installments',
        'status'
    ];

    /**
     * Get payment details
     *
     * @return null|\ArrayObject
     */
    public function details()
    {
        return (new \Ernandesrs\LapiPayment\Services\Payments\LapiPay())->paymentDetails($this);
    }
}
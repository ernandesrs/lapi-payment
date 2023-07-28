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
}
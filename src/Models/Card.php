<?php

namespace Ernandesrs\LapiPayment\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hash',
        'holder_name',
        'last_digits',
        'expiration_date',
        'country',
        'gateway',
        'brand',
        'valid'
    ];
}
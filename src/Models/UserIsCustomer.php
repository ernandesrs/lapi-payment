<?php

namespace Ernandesrs\LapiPayment\Models;

use Illuminate\Database\Eloquent\Model;

class UserIsCustomer extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_is_customers';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'gateway',
        'customer_id'
    ];

    /**
     * Get the user associated with the UserIsCustomer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }

    /**
     * Get customer details from gateway
     *
     * @return null|\ArrayObject
     */
    public function details()
    {
        return (new \Ernandesrs\LapiPayment\Services\Payments\LapiPay())->customerDetails($this);
    }
}
<?php

namespace Ernandesrs\LapiPayment\Models;

trait AsCustomer
{
    /**
     * Get all of the cards for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Card::class, 'user_id', 'id')->where('gateway', config('lapi-payment.gateway'));
    }

    /**
     * Get all of the payments for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class, 'user_id', 'id')->where('gateway', config('lapi-payment.gateway'));
    }

    /**
     * Customer id
     *
     * @return string
     */
    abstract public function customerId(): string;

    /**
     * Customer full name
     *
     * @return string
     */
    abstract public function customerName(): string;

    /**
     * Customer email
     *
     * @return string
     */
    abstract public function customerEmail(): string;

    /**
     * Customer country
     *
     * @return string
     */
    abstract public function customerCountry(): string;

    /**
     * Customer type
     * Tipo de pessoa, individual ou corporation
     *
     * @return string
     */
    public function customerType(): string
    {
        return 'individual';
    }

    /**
     * Customer phone numbers
     *
     * @return array
     */
    abstract public function customerPhoneNumbers(): array;

    /**
     * Customer documents
     *
     * @return array
     */
    abstract public function customerDocuments(): array;

    /**
     * Customer adress
     *
     * @return \Ernandesrs\LapiPayment\Models\Address
     */
    abstract public function customerAddress(): \Ernandesrs\LapiPayment\Models\Address;
}
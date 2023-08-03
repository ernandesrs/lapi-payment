<?php

namespace Ernandesrs\LapiPayment\Models;

trait AsCustomer
{
    /**
     * Get all of the customer for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customer(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserIsCustomer::class, 'user_id', 'id')->where('gateway', config('lapi-payment.gateway'));
    }

    /**
     * Check if this user is a customer
     *
     * @return boolean
     */
    public function isCustomer(): bool
    {
        return $this->customer()->count();
    }

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
     * Customer first name
     *
     * @return string
     */
    abstract public function customerFirstName(): string;

    /**
     * Customer last name
     *
     * @return string
     */
    abstract public function customerLastName(): string;

    /**
     * Customer full name
     *
     * @return string
     */
    public function customerName(): string
    {
        return $this->customerFirstName() . ' ' . $this->customerLastName();
    }

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
     * Customer phone number
     *
     * @return \Ernandesrs\LapiPayment\Models\Phone
     */
    abstract public function customerPhone(): \Ernandesrs\LapiPayment\Models\Phone;

    /**
     * Customer document
     *
     * @return \Ernandesrs\LapiPayment\Models\Document
     */
    abstract public function customerDocument(): \Ernandesrs\LapiPayment\Models\Document;

    /**
     * Customer adress
     *
     * @return \Ernandesrs\LapiPayment\Models\Address
     */
    abstract public function customerAddress(): \Ernandesrs\LapiPayment\Models\Address;
}
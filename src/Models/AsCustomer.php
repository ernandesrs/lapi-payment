<?php

namespace Ernandesrs\LapiPayment\Models;

trait AsCustomer
{
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
}
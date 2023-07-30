<?php

return [
    'card' => [
        'number' => 'Card number must be 16 digits',
        'cvv' => 'Card cvv must be 3 digits',
        'expiration' => 'Card expiration date must be 4 digits. E.g.: 0135 for cards valid until January 2035'
    ],
    'charge' => [
        'amount' => [
            'decimal' => 'Must be a valid price to 2 decimal places. Eg: 10.99'
        ],
        'installments' => [
            'integer' => 'The number of plots must be integer',
            'between' => 'The number of installments must be between :min and :max'
        ]
    ],
    'refund' => [
        'amount' => [
            'decimal' => 'Must be a valid price to 2 decimal places. Eg: 10.99',
            'lte' => 'Value must be less than or equal to :amount'
        ]
    ]
];
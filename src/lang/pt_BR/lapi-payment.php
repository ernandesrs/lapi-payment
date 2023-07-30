<?php

return [
    'card' => [
        'number' => 'O número do cartão deve ter 16 dígitos',
        'cvv' => 'O cvv do cartão deve ter 3 dígitos',
        'expiration' => 'A data de expiração do cartão deve ter 4 dígitos. Ex.: 0135 para cartões válidos até janeiro de 2035'
    ],
    'charge' => [
        'amount' => [
            'decimal' => 'Deve ser um preço válido de até 2 casas decimais. Ex. 10.99'
        ],
        'installments' => [
            'integer' => 'O número de parcelas deve ser inteiro',
            'between' => 'O número de pacelas deve estar entre :min e :max'
        ]
    ],
    'refund' => [
        'amount' => [
            'decimal' => 'Deve ser um preço válido de até 2 casas decimais. Ex. 10.99',
            'lte' => 'Valor deve ser menor ou igual a :amount'
        ]
    ]
];
# LAPI PAYMENT

Sistema de pagamentos para o LAPI(https://github.com/ernandesrs/pproj_lapi).

# GATEWAYS IMPLEMENTADOS
Pagarme: pagarme

# INSTALAÇÃO
> composer require ernandesrs/lapi-payment

# CONFIGURAÇÃO
Guia de configuração do pacote LAPI PAYMENT.

### Variáveis ambientes
No arquivo <b>.env</b> do seu projeto, adicione as seguintes variáveis:
```
PAYMENT_TESTING=true
PAYMENT_DEFAULT_GATEWAY=GATEWAY NAME
PAYMENT_GATEWAY_PAGARME_API_TEST=YOUR API KEY TEST
PAYMENT_GATEWAY_PAGARME_API_LIVE=YOUR API KEY LIVE
```

<b>PAYMENT_TESTING</b> define se o sistema de cobrança está em testes. Se definido como <b><i>false</i></b>, o sistema de cobrança irá efetuar cobranças reais.

<b>PAYMENT_DEFAULT_GATEWAY</b> define a gateway que será utilizada. Veja o [início da documentação as gateways implementadas](#gateways-implementados).

<b>PAYMENT_GATEWAY_PAGARME_API_TEST</b> chave de teste da api(cobranças falsas para testes).

<b>PAYMENT_GATEWAY_PAGARME_API_LIVE</b> chave de produção da api(cobranças reais).

### Adicione o sevice provider
Em <b>/config/app.php</b> adicione <b>ErnandesRS\LapiPayment\LapiPaymentServiceProvider::class</b> no item <b>'providers'</b>. Vai ficar assim:
```php

<?php

return [
    
    // outras configurações... 

    'providers' => [
        // outros providers...
        App\Providers\RouteServiceProvider::class,

        Ernandesrs\LapiPayment\LapiPaymentServiceProvider::class
    ],

    // outras configurações
];

```

### Publique o arquivo de configuração
Na raiz do projeto Laravel, publique o arquivo de configuração com o seguinte comando:
> php artisan vendor:publish --tag=lapi-payment-config

O arquivo de configuração possui campos que podem ser modificados no arquivo de variáveis <b>.env</b>, veja a seção acima <b>['Variáveis ambientes'](#variáveis-ambientes)</b>.

# USO
Para fazer uso é simples, basta usar o facade:
```php

\Ernandesrs\LapiPayment\App\Facades\Payment

```

### Criando/validando um cartão
```php

$card = \Ernandesrs\LapiPayment\App\Facades\Payment::createCard('The Holder Name', '4916626701217934', '156', '0424');
print_r($card);

```

### Fazendo uma cobrança no cartão de crédito
```php

// validar o cartão
$card = \Ernandesrs\LapiPayment\App\Facades\Payment::createCard('The Holder Name', '4916626701217934', '156', '0424');

$cardHash = $card->gateway_card_id;
$amount = 101.98;
$installments = 1;
$extras = [
    'example' => 'example extra data',
    'meta' => 'data',
    'others' => 'information',
    'more' => 'fields'
];

// cobrança com cartão validado
$chargeWithCard = \Ernandesrs\LapiPayment\App\Facades\Payment::chargeWithCard($cardHash, $amount, $installments, $extras);
print_r($chargeWithCard);

```
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

### Faça uso da trait AsCustomer
Na seu modelo de usuário <b><i>\App\Models\User</i></b>, faça uso da trait <b>AsCustomer</b>, seu modelo ficará parecido com isso:
```php

<?php

namespace App\Models;

// outras importações...
use Ernandesrs\LapiPayment\Models\AsCustomer;

class User extends Authenticatable
{
    use AsCustomer;

```

Agora é preciso implementar alguns métodos obrigatórios no modelo User:
```php

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

```

Em sua maioria, os métodos não precisam de explicação e são bem intuitivos, mas algumas são necessários, veja:

#### customerPhoneNumbers()
O método deverá retornar um array simples contendo ao menos um número de telefone do cliente.
```php

abstract public function customerPhoneNumbers(): array;

```

#### customerDocuments()
O método deverá retornar um array de arrays os dados dos documentos do cliente, seguindo exemplo no comentário do código abaixo.
```php

/**
 * 
 * Example:
 * [
 *      [
 *          'type' => 'cpf',
 *          'number' => '00000000011'
 *      ],
 *      [
 *          'type' => 'rg',
 *          'number' => '12345679'
 *      ]
 * ]
 * 
 */
abstract public function customerDocuments(): array;

```

#### customerType()
É um método opcional que define o tipo de cliente(individual/corporation). Por padrão é 'individual'.

# USO
Para fazer uso é simples, basta usar o facade:
```php

\Ernandesrs\LapiPayment\Facades\LapiPay

```

### Criando/validando um cartão
```php

$card = \Ernandesrs\LapiPayment\Facades\LapiPay::createCard('The Holder Name', '4916626701217934', '156', '0424');
print_r($card);

```

### Fazendo uma cobrança no cartão de crédito
```php

// validar o cartão
$card = \Ernandesrs\LapiPayment\Facades\LapiPay::createCard('The Holder Name', '4916626701217934', '156', '0424');

$cardHash = $card->hash;
$amount = 101.98;
$installments = 1;
$extras = [
    'example' => 'example extra data',
    'meta' => 'data',
    'others' => 'information',
    'more' => 'fields'
];

// cobrança com cartão validado
$chargeWithCard = \Ernandesrs\LapiPayment\Facades\Payment::chargeWithCard($cardHash, $amount, $installments, $extras);
print_r($chargeWithCard);

```

### Adicionando dados do cliente na cobrança
Adicionando dados do cliente no registro de cobrança da gateway.
```php

// cliente
$client = \Auth::user();

// validar o cartão
$card = \Ernandesrs\LapiPayment\Facades\LapiPay::createCard('The Holder Name', '4916626701217934', '156', '0424');

$amount = 101.98;
$installments = 1;

// adicionando um cliente e efetuando cobrança
$chargeWithCard = \Ernandesrs\LapiPayment\Facades\LapiPay::addCustomer($client)->chargeWithCard($card->hash, $amount, $installments);
print_r($chargeWithCard);

```
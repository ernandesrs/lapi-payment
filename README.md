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
PAYMENT_GATEWAY_PAGARME_API_ANTIFRAUD=false

```

| CHAVE | DESCRIÇÃO |
| --- |  --- |
| PAYMENT_TESTING | Define se o sistema de cobrança está em testes. Se definido como <b><i>false</i></b>, o sistema de cobrança irá efetuar cobranças reais. |
| PAYMENT_DEFAULT_GATEWAY | Define a gateway que será utilizada. Veja o [início da documentação as gateways implementadas].(#gateways-implementados) |
| PAYMENT_GATEWAY_PAGARME_API_TEST | Chave de teste da api(cobranças falsas para testes). |
| PAYMENT_GATEWAY_PAGARME_API_LIVE | Chave de produção da api(cobranças reais). |
| PAYMENT_GATEWAY_PAGARME_API_ANTIFRAUD | Define se o recurso de antifraude está habilitado na Pagar.me. Quando habilitado, alguns dados extras são obrigatórios em cobranças com cartão de crédito. |

### Adicione o Sevice Provider
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

### (Opcional) Publique o arquivo de configuração
Na raiz do seu projeto Laravel, publique o arquivo de configuração com o seguinte comando:
> php artisan vendor:publish --tag=lapi-payment-config

O arquivo de configuração possui campos que podem ser modificados no arquivo de variáveis <b>.env</b>, veja a seção acima <b>['Variáveis ambientes'](#variáveis-ambientes)</b>.

### Faça uso da trait AsCustomer no modelo User
Na seu modelo de usuário <b><i>\App\Models\User</i></b>, faça o uso da trait <b>AsCustomer</b>, seu modelo ficará parecido com isso:
```php

<?php

namespace App\Models;

// outras importações...
use Ernandesrs\LapiPayment\Models\AsCustomer;

class User extends Authenticatable
{
    use AsCustomer;

```
### Métodos da trait AsCustomer
<b>AsCustomer</b> possui alguns métodos obrigatórios e opcionais que precisam ou podem ser implementados no modelo User, veja:
| MÉTODO | OBRIGATÓRIO | DESCRIÇÃO |
| --- | --- | --- |
| customerId() | Sim | Deverá retornar o id do cliente. |
| customerName() | Sim | Deverá retornar o nome completo do cliente. |
| customerEmail() | Sim | Deverá retornar o email do cliente. |
| customerCountry() | Sim | Deverá retornar o país do cliente. |
| customerPhoneNumbers() | Sim | Deverá retornar um array simples com pelo menos um número de telefone do cliente. |
| customerDocuments() | Sim | Deverá retornar um array contendo subarrays com os documentos do cliente. Cada subarray deve possuir 2 chaves nomeadas: type e number. |
| customerType() | Não | Tipo de cliente, <i>individual</i> ou <i>corporation</i>. O padrão é <i>individual</i>. |
| customerAddress()() | Sim | Deve retornar uma instância da classe <b>\Ernandesrs\LapiPayment\Models\Address</b>, que conterá o endereço do cliente. |

# USO
Para fazer uso é simples, basta usar o facade <b>\Ernandesrs\LapiPayment\Facades\LapiPay</b>:

# EXEMPLOS DE USO
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

### Adicionando produtos na cobrança
Adicionando dados de produtos no registro de cobrança da gateway
```php

// validar o cartão
$card = \Ernandesrs\LapiPayment\Facades\LapiPay::createCard('The Holder Name', '4916626701217934', '156', '0424');

// cliente
$client = \Auth::user();

// adicionando produtos/items
$lapipay = \Ernandesrs\LapiPayment\Facades\LapiPay::addProduct(2109, 'Curso Digital', 99.00, 1, false);
$lapipay->addProduct(19203, 'Notebook Gaming 3i', 3985.94, 1, true);
$lapipay->addProduct(93030, 'Celular Top de Linha', 2985.94, 1, true);
$lapipay->addCustomer(\Auth::user());

$chargeWithcard = $lapipay->chargeWithCard($card->hash, 3985.94 + 99.00 + 2985.94, 1);

var_dump($chargeWithcard);

```
### Adicionando billing(dados de cobrança)
Adicionando dados de cobrança
```php

// card validation
$card = \Ernandesrs\LapiPayment\Facades\LapiPay::createCard('The Holder Name', '4916626701217934', '156', '0424');

$chargeWithcard = \Ernandesrs\LapiPayment\Facades\LapiPay::addCustomer(\Auth::user())
    ->addBilling(\Auth::user()->customerName(), 'Rua Top', '246', '121234500', \Auth::user()->customerCountry(), 'MS', 'Dourados', 'Bairro top', 'Casa')
    ->addProduct(920932, 'Produto Digital Top', 99.99, 1, false)
    ->addProduct(382891, 'Produto Físico Top', 100.98, 1, true)
    ->chargeWithCard($card->hash, 99.99 + 100.98, 1, ['dados' => 'extras']);
var_dump($chargeWithcard);

```
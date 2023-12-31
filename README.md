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
PAYMENT_POSTBACK_URL_LOCAL_TEST=
PAYMENT_DEFAULT_GATEWAY=GATEWAY NAME
PAYMENT_GATEWAY_PAGARME_API_TEST=YOUR API KEY TEST
PAYMENT_GATEWAY_PAGARME_API_LIVE=YOUR API KEY LIVE
PAYMENT_GATEWAY_PAGARME_API_ANTIFRAUD=false

```

| CHAVE | DESCRIÇÃO |
| --- |  --- |
| PAYMENT_TESTING | Define se o sistema de cobrança está em testes. Se definido como <b><i>false</i></b>, o sistema de cobrança irá efetuar cobranças reais. |
| PAYMENT_POSTBACK_URL_LOCAL_TEST | Url de postback para teste local. |
| PAYMENT_DEFAULT_GATEWAY | Define a gateway que será utilizada. Veja o [início da documentação as gateways implementadas](#gateways-implementados). |
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

### Publique o arquivo de configuração
Na raiz do seu projeto Laravel, publique o arquivo de configuração com o seguinte comando:
> php artisan vendor:publish --tag=lapi-payment-config

O arquivo de configuração possui campos que podem ser modificados no arquivo de variáveis <b>.env</b>, veja a seção acima <b>['Variáveis ambientes'](#variáveis-ambientes)</b>. [Veja o arquivo de configurações](src/config/lapi-payment.php) para mais detalhes.

### (Opcional) Publique os arquivos de idiomas
Na raiz do seu projeto Laravel, publique os arquivos de idiomas com o seguinte comando:
> php artisan vendor:publish --tag=lapi-payment-lang

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
| customerId() | Obrigatório | Deverá retornar o id do cliente. |
| customerFirstName() | Obrigatório | Deverá retornar primeiro nome do cliente. |
| customerLastName() | Obrigatório | Deverá retornar sobrenome do cliente. |
| customerEmail() | Obrigatório | Deverá retornar o email do cliente. |
| customerCountry() | Obrigatório | Deverá retornar o país do cliente. |
| customerPhone() | Obrigatório | Deverá retornar uma instância de <b>[\Ernandesrs\LapiPayment\Models\Phone](src/Models/Phone.php)</b>. |
| customerDocument() | Obrigatório | Deverá retornar uma instância de <b>[\Ernandesrs\LapiPayment\Models\Document](src/Models/Document.php)</b>. |
| customerType() | Opcional | Tipo de cliente, <i>individual</i> ou <i>corporation</i>. O padrão é <i>individual</i>. |
| customerAddress()() | Obrigatório | Deve retornar uma instância da classe <b>[\Ernandesrs\LapiPayment\Models\Address](src/Models/Address.php)</b>, que conterá o endereço do cliente. |

Copie e cole, adaptando de acordo com seu modelo User:
```php

    /**
     * Customer id
     *
     * @return string
     */
    public function customerId(): string
    {
        return $this->id;
    }

    /**
     * Customer first name
     *
     * @return string
     */
    public function customerFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * Customer last name
     *
     * @return string
     */
    public function customerLastName(): string
    {
        return $this->last_name;
    }

    /**
     * Customer email
     *
     * @return string
     */
    public function customerEmail(): string
    {
        return $this->email;
    }

    /**
     * Customer country
     *
     * @return string
     */
    public function customerCountry(): string
    {
        return 'br';
    }

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
    public function customerPhone(): \Ernandesrs\LapiPayment\Models\Phone
    {
        return new \Ernandesrs\LapiPayment\Models\Phone(55, 67900000000);
    }

    /**
     * Customer document
     *
     * @return \Ernandesrs\LapiPayment\Models\Document
     */
    public function customerDocument(): \Ernandesrs\LapiPayment\Models\Document
    {
        return \Ernandesrs\LapiPayment\Models\Document::cpf(33983219098);
    }

    /**
     * Customer adress
     *
     * @return \Ernandesrs\LapiPayment\Models\Address
     */
    public function customerAddress(): \Ernandesrs\LapiPayment\Models\Address
    {
        return new \Ernandesrs\LapiPayment\Models\Address(
            'Rua Street',
            7822,
            '29315-000',
            'br',
            'sp',
            'São Paulo',
            'Centro',
            'Apartamento 489 Andar 12'
        );
    }

```

# USO
Para fazer uso é simples, basta usar o facade <b>[\Ernandesrs\LapiPayment\Facades\LapiPay](src/Facades/LapiPay.php)</b>:

## Clientes
### Cadastrando um cliente
Esta ação irá criar um cliente e salvá-lo na base de dados da gateway. A gateway irá retornar um ID, e este ID será armazenado no seu banco de dados como um forma associar rapidamente o cliente à uma transação(pagamento por exemplo).

O exemplo abaixo cria um cliente baseado no usuário injetado(use a trait [AsCustomer](src/Models/AsCustomer.php) e implemente os métodos necessários, [veja mais aqui](#faça-uso-da-trait-ascustomer-no-modelo-user))
```php

$user = \Auth::user();
$customer = \Ernandesrs\LapiPayment\Facades\LapiPay::createCustomer($user);
print_r($customer);

```

O método <i>\Ernandesrs\LapiPayment\Facades\LapiPay::createCustomer</i> possui outros parâmetros: id, name, email, country, etc; estes parâmetros podem ser informados manualmente, mas se forem nulos, os valores serão obtidos automaticamente do $user injetado.

### Recuperandos cliente
Recupere os dados do cliente salvos na base de dados da gateway
```php

// first way
$details = $user->customer()->first()->details();
var_dump($details);

// second way
$customer = $user->customer()->first();
$details = \Ernandesrs\LapiPayment\Facades\LapiPay::customerDetails($customer);
var_dump($details);

```

## Cartões
### Validando e salvando um cartão
O método <i>\Ernandesrs\LapiPayment\Facades\LapiPay::createCard</i> valida um cartão com a gateway e o salva no banco de dados. O cartão pertencerá ao usuário injetado.
```php

$user = \Auth::user();
$card = \Ernandesrs\LapiPayment\Facades\LapiPay::createCard($user, 'The Holder Name', '4916626701217934', '156', '0424');
print_r($card);

```

### Erros na validação do cartão
Os dados do cartão passarão por uma validação prévia, antes de serem enviados para a gateway. Ao ocorrer qualquer erro na validação, uma exceção <i>\Ernandesrs\LapiPayment\Exceptions\InvalidDataException</i> será lançada e um array com as mensagens com detalhes sobre o(s) erro(s) serão armazenados na sessão do usuário(este array é um array retornado pelo validador do Laravel).
Veja abaixo a forma para recuperar essas mensagens:
```php

try {
    // try validate, create and save card
    $card = \Ernandesrs\LapiPayment\Facades\LapiPay::createCard($user, 'The Holder Name', '4916626701217934', '156', '0424');

    // success
    print_r($card);
} catch(\Ernandesrs\LapiPayment\Exceptions\InvalidDataException $e) {
    // get error messages
    $errors = \Ernandesrs\LapiPayment\Facades\LapiPay::errorMessages();

    // fail
    print_r($errors);
}

```

### Recuperandos cartões
Recupera todos os cartões validados e salvos para o usuário.
```php

$user = \Auth::user();
$cards = $user->cards()->get();
print_r($cards);

```

## Cobranças
### Adicionando cliente
Adicionando dados do cliente no registro de cobrança da gateway.
```php

// get customer
$customer = \Auth::user();

// add customer
$lapipay = \Ernandesrs\LapiPayment\Facades\LapiPay::addCustomer($customer)

```

### Adicionando billing(dados de cobrança)
Adicionando dados de cobrança. Esta ação irá definir o nome e os dados de endereço que será obtido pelo método customerAddress() [implementado aqui](#métodos-da-trait-ascustomer).
```php

// get customer
$customer = \Auth::user();

// add customer
$lapipay = \Ernandesrs\LapiPayment\Facades\LapiPay::addBilling($customer)

```

### Adicionando produtos
Adicionando dados de produtos no registro de cobrança da gateway.
```php

// adicionando um produto/item
$lapipay = \Ernandesrs\LapiPayment\Facades\LapiPay::addProduct(2109, 'Curso Digital', 99.00, 1, false);

// adicionando vários produtos/itens
$lapipay = \Ernandesrs\LapiPayment\Facades\LapiPay::addProduct(2109, 'Produto Digital', 99.00, 1, false)
    ->addProduct(9231, 'Produto Físico', 102.97, 1, true)
    ->addProduct(9231, 'Outro Produto', 12.97, 1, false)
    ->addProduct(9231, 'Mais Um Produto', 77.97, 1, false);

```

### Realizando cobrança
Efetuando uma cobrança no cartão de crédito. (O valor a ser cobrado não é calculado automaticamente ao adicionar os produtos/itens)
```php

// get customer
$customer = \Auth::user();

// get a registered card
$card = $customer->cards()->first();

$amount = 99.00 + 102.97 + 12.97;
$installments = 1;

$lapipay = \Ernandesrs\LapiPayment\Facades\LapiPay::addCustomer($customer)
    ->addBilling($customer)
    ->addProduct(2109, 'Produto Digital', 99.00, 1, false)
    ->addProduct(9231, 'Produto Físico', 102.97, 1, true)
    ->addProduct(9231, 'Outro Produto', 12.97, 1, false)
    ->chargeWithCard($customer, $card, $amount, $installments);

var_dump($lapipay);

```

### Erros de validação nos dados da cobrança
Os dados de cobrança(valor e parcela) passarão por uma validação prévia, antes de serem enviados para a gateway. Ao ocorrer qualquer erro na validação, uma exceção <i>\Ernandesrs\LapiPayment\Exceptions\InvalidDataException</i> será lançada e um array com as mensagens com detalhes sobre o(s) erro(s) serão armazenados na sessão do usuário(este array é um array retornado pelo validador do Laravel).
Veja abaixo a forma para recuperar essas mensagens:
```php

try {
    // try charge customer
    $payment = \Ernandesrs\LapiPayment\Facades\LapiPay::addCustomer($customer)
        ->addBilling($customer)
        ->addProduct(2109, 'Produto Digital', 99.00, 1, false)
        ->chargeWithCard($customer, $card, $amount, $installments);

    // success
    print_r($payment);
} catch(\Ernandesrs\LapiPayment\Exceptions\InvalidDataException $e) {
    // get error messages
    $errors = \Ernandesrs\LapiPayment\Facades\LapiPay::errorMessages();

    // fail
    print_r($errors);
}

```

Outras exceções(todas no namespace <i>\Ernandesrs\LapiPayment\Exceptions</i>) podem ser lançadas ao tentar realizar uma cobrança e que podem ser capturadas, são elas:
| Exceção | Descrição |
| --- | --- |
| InvalidCardException | Cartão inválido |
| ChargedbackPaymentException | Cobrado de volta |
| RefundedPaymentException | Pagamento devolvido |
| RefusedPaymentException | Pagamento recusado |

### Realizando reembolsos
Efetuando um reembolso parcial.
```php

$payment = \Auth::user()->payments()->first();

$refund = \Ernandesrs\LapiPayment\Facades\LapiPay::refundPayment($payment, 50.00, ['reason' => 'Lorem ipsum dolor sit']);
var_dump($refund);

```

Efetuando um reembolso total.
```php

$payment = \Auth::user()->payments()->first();

$refund = \Ernandesrs\LapiPayment\Facades\LapiPay::refundPayment($payment, null, ['reason' => 'Lorem ipsum dolor sit']);
var_dump($refund);

```

### Erros de validação nos dados de reembolso
Ao reembolsar, o valor será validado antes e uma exceção <i>\Ernandesrs\LapiPayment\Exceptions\InvalidDataException</i> será lançada em caso de falha na validação. Veja baixo como capturar informações sobre o erro ocorrido.
```php

try {
    // get payment
    $payment = $user->payments()->first();

    // try refund payment
    $refund = \Ernandesrs\LapiPayment\Facades\LapiPay::refundPayment($payment, null, ['reason' => 'Lorem ipsum dolor sit']);
    
    // success
    var_dump($refund);
} catch(\Ernandesrs\LapiPayment\Exceptions\InvalidDataException $e) {
    // get error messages
    $errors = \Ernandesrs\LapiPayment\Facades\LapiPay::errorMessages();

    // fail
    print_r($errors);
}

```
Outras exceções(ambas no namespace <i>\Ernandesrs\LapiPayment\Exceptions</i>) podem ser lançadas ao tentar realizar um reembolso, são elas:
| Exceção | Descrição |
| --- | --- |
| PaymentHasAlreadyBeenRefundedException | Pagamento já reembolsado |

### Obtendo detalhes do pagamento
Obtendo detalhes do pagamento registrado pela gateway configurada.
```php

// first way
$details = $user->payments()->first()->details();
var_dump($details);

// second way
$payment = $user->payments()->first();
$details = \Ernandesrs\LapiPayment\Facades\LapiPay::paymentDetails($payment);
var_dump($details);

```

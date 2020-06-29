# Zuora-API

A simple PHP package to integrate the Zuora REST API in your project.

<br />

## Installation
You can directly install the package with:
```bash
composer require lunalabs-srl/zuora-api
```
<br />

## Test the package
Create a simple PHP file with these lines:
```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use LunaLabs\ZuoraApi\ZuoraApi;
use LunaLabs\ZuoraApi\ZuoraCallout;
use LunaLabs\ZuoraApi\Exceptions\ZuoraApiException;

// Zuora credentials.
$zuoraConfig = [
    'clientId'     => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
    'clientSecret' => 'XXXXX=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX=XXX',
    'baseUri'      => 'https://rest.sandbox.eu.zuora.com',
    'apiVersion'   => 'v1'
];

try {

    // Instance.
    $zuora = new ZuoraApi($zuoraConfig);

    // Retrieves all the stored accounts.
    $response = $zuora->account()->all();

    // Prints the result as JSON.
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (ZuoraApiException $e) {

    // In case of error, you can handle the formatted object response through some useful properties.
    header('Content-Type: application/json');
    echo json_encode($e->errorFormatter());
}
```

<br />

## Available methods on Account entity
```php
$accountData = [
    'name'          => 'John Doe',
    'notes'         => 'Optional notes',
    'currency'      => 'EUR',
    'paymentTerm'   => 'Due Upon Receipt',
    'autoPay'       => false,
    'billCycleDay'  => 0,
    'billToContact' => [
        'firstName' => 'John',
        'lastName'  => 'Doe',
        'workEmail' => 'john.doe@domain.com',
        'address1'  => 'Strange street 69',
        'city'      => 'CityName',
        'country'   => 'CountryName',        
        'state'     => 'ST',
        'zipCode'   => '01234',
    ],
    'invoiceDeliveryPrefsEmail' => false,
    'invoiceDeliveryPrefsPrint' => false
];

$accountNewData = [
    'name'          => 'John Doe Jr',
    'billToContact' => [
        'lastName'  => 'Doe Jr',
    ],
    'additionalEmailAddresses' => [
        "john.doe.jri@domain.com",
    ]
];

try {
    $response = $zuora->account()->all();
    $response = $zuora->account()->get('A00000001');
    $response = $zuora->account()->create($accountData);
    $response = $zuora->account()->update('A00000001', $accountNewData);
    $response = $zuora->account()->delete('1adcc59c723b9c3f01723d09813c2965');
    $response = $zuora->account()->query('select Id, Name, AccountNumber, Balance from Account');
    $response = $zuora->account()->fields();
    $response = $zuora->account()->field('AccountNumber');
    $response = $zuora->account()->prepareFieldsQuery($response, ['SequenceSetId', 'TaxExemptEntityUseCode', 'TotalDebitMemoBalance', 'UnappliedCreditMemoAmount']);
    $response = $zuora->account()->query('select '.$response.' from account');

} catch (ZuoraApiException $e) {

    // In case of error, you can handle the formatted object response through some useful properties.
    header('Content-Type: application/json');
    echo json_encode($e->errorFormatter());
}
```

<br />

## Available methods on PaymentMethod entity
```php
// If you want to associate a tokenized credit card to an existing Zuora account, you have to use a payload like this:

$tokenizedCard = [
    'AccountId'                 => 'ZUORA_ACCOUNT_ID',
    'Type'                      => 'CreditCardReferenceTransaction',
    'UseDefaultRetryRule'       => true,
    'TokenId'                   => 'CARD_ID_FROM_YOUR_PAYMENT_GATEWAY',
    'SecondTokenId'             => 'REFERENCE_ID_FROM_YOUR_PAYMENT_GATEWAY'
];

$tokenizedSepa = [
    'AccountId'                 => 'ZUORA_ACCOUNT_ID',
    'Type'                      => 'BankTransfer',
    'BankTransferType'          => 'SEPA',
    'UseDefaultRetryRule'       => true,
    'BankTransferAccountNumber' => 'IT4000000000000000000000000', // The user's IBAN
    'MandateReceived'           => 'Yes',
    'ExistingMandate'           => 'Yes',
    'MandateID'                 => 'SLMP000000000'
];

try {
    // Retrieves the payment method details by its ID.
    $response = $zuora->paymentMethod()->get('PAYMENT_METHOD_ID');

    // Creates the given payment method (credit card or SEPA).
    // This response will return the payment ID to use to set the default payment.
    $response = $zuora->paymentMethod()->create($tokenizedCard);

    // Updates the desired payment method by its ID with the new data.
    $response = $zuora->paymentMethod()->update('PAYMENT_METHOD_ID', $newData);

    // Deletes the given payment method ID (you can not delete the default payment method).
    $response = $zuora->paymentMethod()->delete('PAYMENT_METHOD_ID_TO_DELETE');

    // Associates the given payment method to the given existing account ID, marking it as default payment method.
    $response = $zuora->paymentMethod()->setDefaultPayment('ZUORA_ACCOUNT_ID', 'PAYMENT_METHOD_ID');

} catch (ZuoraApiException $e) {
    // ...
}
```
This call will return an object with the property **Success = true** and the **unique ID of the registered payment**.

<br />

## Available methods on Product entity
```php
try {
    // Retrieves the product details by its ID.
    $response = $zuora->product()->get('PRODUCT_ID');

    // Retrieves all the rate plans of the given product ID (to be used during the order creation).
    $response = $zuora->product()->getRatePlans('PRODUCT_ID');

} catch (ZuoraApiException $e) {
    // ...
}
```

<br />

## Available methods on Order entity
```php
$orderData = [
    'description'           => 'Test order description',
    'existingAccountNumber' => 'ZUORA_ACCOUNT_ID',
    'orderDate'             => '2020-01-01',
    'subscriptions'         => [
        ...
    ]
];

try {
    // Retrieves the order details by the order number.
    $response = $zuora->order()->get('ORDER_NUMBER');

    // Creates the order and the subscription for the desired account.
    $response = $zuora->order()->create($orderData);

    // Deletes the given order by the order number.
    $response = $zuora->order()->delete('ORDER_NUMBER');

} catch (ZuoraApiException $e) {
    // ...
}
```

<br />

# Available methods on other entities

The other entites provide similar methods to retrieve, save, update and delete a record.
Simply open the desired entity class and see what it covers.
<br />

If you need a particular call on a non-existing method on a entity, you can use the common **getResource()** method as described here:
```php
$response = $zuora->account()->getResource('POST', '/accounts/ZUORA_ACCOUNT_ID', $data);
```
 
<br />

## Callout

It is possible to handle the **Zuora callout** by specifying custom username and password for each callout.
To instantiate the callout handler, write these lines:
```php
$callout  = new ZuoraCallout(['username' => 'customUsername', 'password' => 'customPassword']);
$response = $callout->getResponse();
```

<br />

If you want to **log the callout response**, you can inject you custom logger system as second parameter.
Pay attention that your logger must have a "**write()**" method inside, as shown in this simple example below.
```php
class Log
{
    public function write($input)
    {
        $path = "./callout.log";
        error_log(json_encode($input), 3, $path);
    }
}

$customLog = new Log;
$callout   = new ZuoraCallout(['username' => 'customUsername', 'password' => 'customPassword'], $customLog);
$response  = $callout->getResponse();
```

<br />

## TODO

### Factory entities
- [x] accounts
- [ ] catalog
- [ ] contacts
- [ ] invoices
- [x] orders
- [ ] paymentgateways
- [x] paymentmethod
- [x] payments
- [x] product
- [ ] rate-plan
- [ ] refunds
- [x] subscriptions
- [ ] transactions

### Testing
- [x] Unit testing

### Pagination
- [ ] Handle the pagination

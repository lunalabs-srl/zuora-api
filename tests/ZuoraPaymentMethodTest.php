<?php
/**
 * This file is part of the Zuora API package.
 *
 * (c) Alessandro Orrù <alessandro.orru@aleostudio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LunaLabs\ZuoraApi\Test;

use LunaLabs\ZuoraApi\ZuoraApi;
use LunaLabs\ZuoraApi\Exceptions\ZuoraApiException;


class ZuoraPaymentMethodTest extends ZuoraApiTest
{
    private $zuora;

    private $zuoraAccountId;
    private $paymentMethodId;
    private $paymentMethodTokenId;
    private $paymentMethodSecondTokenId;


    public function setUp(): void
    {
        parent::setUp();
        $this->zuora = new ZuoraApi($this->config);

        $this->zuoraAccountId             = 'ZUORA_ACCOUNT_ID';
        $this->paymentMethodId            = 'ZUORA_PAYMENT_METHOD_ID';
        $this->paymentMethodTokenId       = 'CARD_ID_FROM_YOUR_PAYMENT_GATEWAY';
        $this->paymentMethodSecondTokenId = 'REFERENCE_ID_FROM_YOUR_PAYMENT_GATEWAY';
    }


    public function testZuoraPaymentMethodWithRightData()
    {
        $tokenizedCard = [
            'AccountId'           => $this->zuoraAccountId,
            'Type'                => 'CreditCardReferenceTransaction',
            'UseDefaultRetryRule' => true,
            'TokenId'             => $this->paymentMethodTokenId,
            'SecondTokenId'       => $this->paymentMethodSecondTokenId
        ];

        $this->assertArrayHasKey('AccountId',           $tokenizedCard, "The payload does not contains the 'AccountId'");
        $this->assertArrayHasKey('Type',                $tokenizedCard, "The payload does not contains the 'Type'");
        $this->assertArrayHasKey('UseDefaultRetryRule', $tokenizedCard, "The payload does not contains the 'UseDefaultRetryRule'");
        $this->assertArrayHasKey('TokenId',             $tokenizedCard, "The payload does not contains the 'TokenId'");
        $this->assertArrayHasKey('SecondTokenId',       $tokenizedCard, "The payload does not contains the 'SecondTokenId'");

        $result = $this->zuora->paymentMethod()->create($tokenizedCard);

        $this->assertInstanceOf('StdClass', $result);
        $this->assertArrayHasKey('Success', (array) $result, "Success property not found'");
        $this->assertTrue($result->Success);
        $this->assertArrayHasKey('Id', (array) $result, "The operation Id property missing'");
    }


    public function testZuoraPaymentMethodWithWrongData()
    {
        $tokenizedCard = ['wrongProperty' => 'wrongValue'];
        $this->expectException(ZuoraApiException::class);
        $this->expectExceptionMessageMatches('/^{"Success":false(.*)$/i');
        $result = $this->zuora->paymentMethod()->create($tokenizedCard);
    }


    public function testZuoraSetDefaultPaymentWithRightData()
    {
        $result = $this->zuora->paymentMethod()->setDefaultPayment($this->zuoraAccountId, $this->paymentMethodId);
        $this->assertInstanceOf('StdClass', $result);
        $this->assertArrayHasKey('Success', (array) $result, "Success property not found'");
        $this->assertTrue($result->Success);
        $this->assertArrayHasKey('Id', (array) $result, "The operation Id property missing'");
    }


    public function testZuoraSetDefaultPaymentWithWrongAccountId()
    {
        $this->expectException(ZuoraApiException::class);
        $this->expectExceptionMessageMatches('/^{"fault(.*)$/i');
        $result = $this->zuora->paymentMethod()->setDefaultPayment('wrongAccountId', $this->paymentMethodId);
    }


    public function testZuoraSetDefaultPaymentWithWrongPaymentMethodId()
    {
        $this->expectException(ZuoraApiException::class);
        $this->expectExceptionMessageMatches('/^{"Success":false(.*)$/i');
        $result = $this->zuora->paymentMethod()->setDefaultPayment($this->zuoraAccountId, 'wrongPaymentMethodId');
    }
}
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


class ZuoraAccountTest extends ZuoraApiTest
{
    private $zuora;


    public function setUp(): void
    {
        parent::setUp();
        $this->zuora = new ZuoraApi($this->config);
    }


    public function testZuoraAccountGetAllRecords()
    {
        $result = $this->zuora->account()->all();
        $this->assertInstanceOf('StdClass', $result);
        $this->assertArrayHasKey('done', (array) $result, "Done property not found'");
        $this->assertTrue($result->done);
    }


    public function testZuoraAccountGetRecordFound()
    {
        $result = $this->zuora->account()->get('A00000001', false);
        $this->assertInstanceOf('StdClass', $result);
        $this->assertArrayHasKey('success', (array) $result, "Success property not found'");
        $this->assertTrue($result->success);
    }


    public function testZuoraAccountGetRecordNotFound()
    {
        $result = $this->zuora->account()->get('NotExistingID', false);
        $this->assertInstanceOf('StdClass', $result);
        $this->assertArrayHasKey('success', (array) $result, "Success property not found'");
        $this->assertFalse($result->success);
    }


    public function testZuoraAccountCreateAccountWithWrongData()
    {
        $accountData = ['name' => 'John Doe'];
        $result = $this->zuora->account()->create($accountData);
        $this->assertInstanceOf('StdClass', $result);
        $this->assertArrayHasKey('success', (array) $result, "Success property not found'");
        $this->assertFalse($result->success);
    }


    public function testZuoraAccountCreateAccountWithRightData()
    {
        $accountData = [
            'name'          => 'John Doe',
            'currency'      => 'EUR',
            'billCycleDay'  => 0,
            'autoPay'       => false,
            'billToContact' => [
                'firstName' => 'John',
                'lastName'  => 'Doe',
                'workEmail' => 'john.doe@domain.com',
                'address1'  => 'Strange street 69',
                'city'      => 'CityName',
                'country'   => 'Italy',
                'state'     => 'ST',
                'zipCode'   => '01234'
            ]
        ];

        $result = $this->zuora->account()->create($accountData);
        $this->assertInstanceOf('StdClass', $result);
        $this->assertArrayHasKey('success', (array) $result, "Success property not found'");
        $this->assertTrue($result->success);
        $this->assertArrayHasKey('accountId', (array) $result, "The created accountId property missing'");
        $this->assertArrayHasKey('accountNumber', (array) $result, "The created accountNumber property missing'");

        $ids = [];
        $ids['accountId']     = $result->accountId;
        $ids['accountNumber'] = $result->accountNumber;

        return $ids;
    }


    /**
     * @depends testZuoraAccountCreateAccountWithRightData
     */
    public function testZuoraAccountUpdateExistingAccount(array $ids)
    {
        $updatedData = ['name' => 'John Doe Updated', 'billToContact' => ['lastName' => 'Doe Updated']];
        $result = $this->zuora->account()->update($ids['accountNumber'], $updatedData);
        $this->assertInstanceOf('StdClass', $result);
        $this->assertArrayHasKey('success', (array) $result, "Success property not found'");
        $this->assertTrue($result->success);
    }


    public function testZuoraAccountUpdateNotExistingAccount()
    {
        $updatedData = ['name' => 'John Doe Updated', 'billToContact' => ['lastName' => 'Doe Updated']];
        $result = $this->zuora->account()->update('NotExistingAccount', $updatedData);
        $this->assertInstanceOf('StdClass', $result);
        $this->assertArrayHasKey('success', (array) $result, "Success property not found'");
        $this->assertFalse($result->success);
    }


    /**
     * @depends testZuoraAccountCreateAccountWithRightData
     */
    public function testZuoraAccountDeleteExistingAccount(array $ids)
    {
        $result = $this->zuora->account()->delete($ids['accountId']);
        $this->assertInstanceOf('StdClass', $result);
        $this->assertArrayHasKey('success', (array) $result, "Success property not found'");
        $this->assertTrue($result->success);
    }


    public function testZuoraAccountDeleteNotExistingAccount()
    {
        $this->expectException(ZuoraApiException::class);
        $result = $this->zuora->account()->delete('NotExistingAccount');
    }


    public function testZuoraAccountGetAccountFields()
    {
        $result = $this->zuora->account()->fields();
        $this->assertInstanceOf('StdClass', $result);
        $totalFields = count((array) $result);
        $this->assertGreaterThan(0, $totalFields);
    }


    public function testZuoraAccountGetExistingAccountField()
    {
        $result = $this->zuora->account()->field('AccountNumber');
        $this->assertInstanceOf('StdClass', $result);
        $this->assertArrayHasKey('name', (array) $result, "name property not found'");
    }


    public function testZuoraAccountGetNotExistingAccountField()
    {
        $result = $this->zuora->account()->field('NotExistingField');
        $this->assertInstanceOf('StdClass', $result);
        $this->assertArrayHasKey('success', (array) $result, "Success property not found'");
        $this->assertFalse($result->success);
    }
}
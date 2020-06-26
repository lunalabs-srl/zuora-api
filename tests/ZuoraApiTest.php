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
use LunaLabs\ZuoraApi\Http\Client;
use LunaLabs\ZuoraApi\Exceptions\ZuoraApiException;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\ClientException;


class ZuoraApiTest extends TestCase
{
    protected $config;


    public function setUp(): void
    {
        $this->config = [
            'clientId'     => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
            'clientSecret' => 'XXXXX=XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX=XXX',
            'baseUri'      => 'https://rest.sandbox.eu.zuora.com',
            'apiVersion'   => 'v1'
        ];
    }


    public function testZuoraInstanceWithoutConfig()
    {
        $this->expectException(ZuoraApiException::class);
        $zuora = new ZuoraApi();
    }


    public function testConfigKeys()
    {
        $this->assertArrayHasKey('clientId',     $this->config, "The config does not contains the 'clientId'");
        $this->assertArrayHasKey('clientSecret', $this->config, "The config does not contains the 'clientSecret'");
        $this->assertArrayHasKey('baseUri',      $this->config, "The config does not contains the 'baseUri'");
        $this->assertArrayHasKey('apiVersion',   $this->config, "The config does not contains the 'apiVersion'");
    }


    public function testConfigBaseUri()
    {
        $urlRegex = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
        $result = (bool) preg_match($urlRegex, $this->config['baseUri']);
        $this->assertEquals(true, $result, "The config URL is not a valid URL");
    }


    public function testGetAccessToken()
    {
        $client = new Client($this->config);
        $token  = $client->getToken();
        $this->assertArrayHasKey('access_token', (array) $token, "The retrieved token object is invalid'");
    }


    public function testGetAccessTokenWithWrongCredentials()
    {
        $wrongConfig = $this->config;
        $wrongConfig['clientId'] = 'wrongString';
        $client = new Client($wrongConfig);
        $this->expectException(ZuoraApiException::class);
        $token = $client->getToken();
    }


    public function testIfMethodExists()
    {
        $zuora  = new ZuoraApi($this->config);
        $result = $zuora->account()->all();
        $this->assertInstanceOf('StdClass', $result);
    }


    public function testIfMethodNotExists()
    {
        $this->expectException(BadMethodCallException::class);
        $zuora  = new ZuoraApi($this->config);
        $result = $zuora->account()->fakeMethod();
    }
}
<?php
/**
 * This file is part of the Zuora API package.
 *
 * (c) Alessandro Orrù <alessandro.orru@aleostudio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LunaLabs\ZuoraApi\Entities;

// Package classes.
use LunaLabs\ZuoraApi\Http\Client;
use LunaLabs\ZuoraApi\Traits\Utilities;
use LunaLabs\ZuoraApi\Exceptions\ZuoraApiException;

// External packages.
use ReflectionClass;
use ReflectionException;
use GuzzleHttp\Exception\GuzzleException;
use BadMethodCallException;


abstract class Entity
{
    use Utilities;


    /**
     * @var Client $client
     */
    protected $client;


    /**
     * Base entity constructor.
     *
     * @param $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }


    /**
     * Throws an exception if the called method is not implemented in the desired entity.
     *
     * @param  string $name
     * @param  array  $arguments
     * @throws ReflectionException
     */
    public function __call(string $name, array $arguments)
    {
        throw new BadMethodCallException('The entity "'.(new ReflectionClass($this))->getShortName().'" does not implement the "'.$name.'()" method.');
    }


    /**
     * Performs a ZOQL query (SQL syntax like) to retrieve a specified fields of an entity (or entities).
     *
     * - No Complex Queries​:     Zuora does not support complex queries and joining for query().
     * - No Aggregate Functions: Nested aggregate functions are not supported for query().
     * - No Wild Card Support:   You cannot use the asterisk (*) for field names. You must explicitly specify a field name.
     * - No Order By Support:    ZOQL does not support sorting results in ascending or descending order.
     *
     * @link   https://www.zuora.com/developer/api-reference/#tag/Actions
     * @link   https://www.zuora.com/developer/api-reference/#operation/Action_POSTquery
     *
     * @param  string $query
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function query(string $query): object
    {
        return $this->client->request('POST', $this->client->getApiVersion() . '/action/query', [ 'json' => [ 'queryString' => $query ]]);
    }


    /**
     * Performs a call to a specified endpoint (only the URL part after the API version) with a specified verb and data
     * to send. This method is useful for custom Zuora flows.
     *
     * @param  string $verb
     * @param  string $endpoint
     * @param  array  $data
     * @param  array  $headers
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function getResource(string $verb, string $endpoint, array $data = [], array $headers = [])
    {
        return $this->client->request($verb, $this->client->getApiVersion() . $endpoint, [ 'json' => $data ], $headers)->toObject();
    }
}

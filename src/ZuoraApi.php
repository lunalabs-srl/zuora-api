<?php
/**
 * This file is part of the Zuora API package.
 *
 * (c) Alessandro Orrù <alessandro.orru@aleostudio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LunaLabs\ZuoraApi;

// Package classes.
use LunaLabs\ZuoraApi\Http\Client;
use LunaLabs\ZuoraApi\Entities\Entity;
use LunaLabs\ZuoraApi\Exceptions\ZuoraApiException;


class ZuoraApi
{
    /**
     * @var Client $client
     */
    protected $client;


    /**
     * Zuora constructor.
     *
     * @param  array  $config Zuora configuration.
     * @param  Client $client The Guzzle HTTP client.
     * @throws ZuoraApiException
     */
    public function __construct(array $config = null, Client $client = null)
    {
        if (is_null($client)) {
            if (is_null($config)) throw new ZuoraApiException('The Zuora auth configuration is missing');
            $client = new Client($config);
        }

        $this->client = $client;
    }


    /**
     * Return an instance of a Resource based on the method called.
     *
     * @param  string $name
     * @param  mixed $args
     * @return Entity
     */
    public function __call(string $name, $args): Entity
    {
        $resource = 'LunaLabs\\ZuoraApi\\Entities\\'.ucfirst($name);

        return new $resource($this->client, ...$args);
    }
}

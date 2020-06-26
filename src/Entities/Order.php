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
use LunaLabs\ZuoraApi\Exceptions\ZuoraApiException;

// External packages.
use GuzzleHttp\Exception\GuzzleException;


class Order extends Entity
{
    /**
     * Retrieves the desired order by his order number on Zuora.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/GET_Order
     *
     * @param  string $orderNumber
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function get(string $orderNumber): object
    {
        return $this->client->request('GET', $this->client->getApiVersion().'/orders/'.$orderNumber, [])->toObject();
    }


    /**
     * Creates a new order with the related subscription for the specified account.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/POST_Order
     *
     * @param  array  $data
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function create(array $data): object
    {
        return $this->client->request('POST', $this->client->getApiVersion().'/orders', [ 'json' => $data ])->toObject();
    }


    /**
     * Deletes the specified order by its order number.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/DELETE_Order
     *
     * @param  string $orderNumber
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function delete(string $orderNumber): object
    {
        return $this->client->request('DELETE', $this->client->getApiVersion().'/orders/'.$orderNumber, [])->toObject();
    }
}

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


class Product extends Entity
{
    /**
     * Retrieves the product details by its product ID.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/Object_GETProduct
     *
     * @param  string $id
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function get(string $id): object
    {
        return $this->client->request('GET', $this->client->getApiVersion().'/object/product/'.$id, [])->toObject();
    }


    /**
     * Retrieves the product rate plans by the product ID.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/GET_ProductRatePlans
     *
     * @param  string $id
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function getRatePlans(string $id): object
    {
        return $this->client->request('GET', $this->client->getApiVersion().'/rateplan/'.$id.'/productRatePlan', [])->toObject();
    }
}

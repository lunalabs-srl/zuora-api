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


class Invoice extends Entity
{
    /**
     * Retrieves the invoice details by its invoice ID.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/Object_GETInvoice
     *
     * @param  string $id
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function get(string $id): object
    {
        return $this->client->request('GET', $this->client->getApiVersion().'/object/invoice/'.$id, [])->toObject();
    }
}

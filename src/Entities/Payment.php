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


class Payment extends Entity
{
    /**
     * Retrieves all the stored payments.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/GET_RetrieveAllPayments
     *
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function all(): object
    {
        return $this->client->request('GET', $this->client->getApiVersion().'/payments', [])->toObject();
    }


    /**
     * Retrieves the desired payment by his ID on Zuora.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/GET_Payment
     *
     * @param  string $id
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function get(string $id): object
    {
        return $this->client->request('GET', $this->client->getApiVersion().'/payments/'.$id, [])->toObject();
    }


    /**
     * Creates a new payment by the given data.
     * If the creation goes right, it returns an object with the payment ID, number, and other infos.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/POST_CreatePayment
     *
     * @param  array  $data
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function create(array $data): object
    {
        return $this->client->request('POST', $this->client->getApiVersion().'/payments', [ 'json' => $data ])->toObject();
    }


    /**
     * Update an existing payment by the given ID and the new data.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/PUT_UpdatePayment
     *
     * @param  string $id
     * @param  array  $data
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function update(string $id, array $data): object
    {
        return $this->client->request('PUT', $this->client->getApiVersion().'/payments/'.$id, [ 'json' => $data ])->toObject();
    }


    /**
     * Deletes a payment by the specified ID.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/DELETE_Payment
     *
     * @param  string $id
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function delete(string $id): object
    {
        return $this->client->request('DELETE', $this->client->getApiVersion().'/payments/'.$id, [])->toObject();
    }


    /**
     * Unapply the payment by the specified ID.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/PUT_UnapplyPayment
     *
     * @param  string $id
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function unapply(string $id): object
    {
        return $this->client->request('PUT', $this->client->getApiVersion().'/payments/'.$id.'/unapply', [])->toObject();
    }


    /**
     * Refund the payment by the specified ID with the given reason and amount.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/POST_RefundPayment
     *
     * @param  string $id
     * @param  array  $data
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function refund(string $id, array $data): object
    {
        return $this->client->request('POST', $this->client->getApiVersion().'/payments/'.$id.'/refunds', [ 'json' => $data ])->toObject();
    }
}

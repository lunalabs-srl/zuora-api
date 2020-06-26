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


class PaymentMethod extends Entity
{
    /**
     * Retrieves the desired payment method by his account ID on Zuora.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/Object_GETPaymentMethod
     *
     * @param  string $id
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function get(string $id): object
    {
        return $this->client->request('GET', $this->client->getApiVersion().'/object/payment-method/'.$id, [])->toObject();
    }


    /**
     * Creates a new payment method by binding the Credit Card/SEPA token to an existing account into Zuora.
     * The Credit Card/SEPA token must be already generated by your payment gateway (example SlimPay, GestPay ...).
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/Object_POSTPaymentMethod
     *
     * @param  array  $data
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function create(array $data): object
    {
        return $this->client->request('POST', $this->client->getApiVersion().'/object/payment-method', [ 'json' => $data ])->toObject();
    }


    /**
     * Update an existing payment method by the given ID and the new data.
     * It will return "success: true" if all goes right.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/Object_PUTPaymentMethod
     *
     * @param  string $id
     * @param  array  $data
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function update(string $id, array $data): object
    {
        return $this->client->request('PUT', $this->client->getApiVersion().'/object/payment-method/'.$id, [ 'json' => $data ])->toObject();
    }


    /**
     * Deletes the existing payment method by the specified ID.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/Object_DELETEPaymentMethod
     *
     * @param  string $id
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function delete(string $id): object
    {
        return $this->client->request('DELETE', $this->client->getApiVersion().'/object/payment-method/'.$id, [])->toObject();
    }


    /**
     * Sets the given payment ID as default payment for the given account ID.
     *
     * @param  string $accountId
     * @param  string $paymentId
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function setDefaultPayment(string $accountId, string $paymentId): object
    {
        return $this->client->request('PUT', $this->client->getApiVersion().'/object/account/'.$accountId, [ 'json' => [ 'DefaultPaymentMethodId' => $paymentId ] ])->toObject();
    }
}
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


class Subscription extends Entity
{
    /**
     * Retrieves all the stored subscriptions for the given account.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/GET_SubscriptionsByAccount
     *
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function all(string $accountId): object
    {
        return $this->client->request('GET', $this->client->getApiVersion().'/subscriptions/accounts/'.$accountId, [])->toObject();
    }


    /**
     * Retrieves the desired subscription by his ID on Zuora.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/GET_SubscriptionsByKey
     *
     * @param  string $id
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function get(string $id): object
    {
        return $this->client->request('GET', $this->client->getApiVersion().'/subscriptions/'.$id, [])->toObject();
    }


    /**
     * Creates a new subscription by the given data.
     * If the creation goes right, it returns an object with the subscription ID, number, and other infos.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/POST_Subscription
     *
     * @param  array  $data
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function create(array $data): object
    {
        return $this->client->request('POST', $this->client->getApiVersion().'/subscriptions', [ 'json' => $data ])->toObject();
    }


    /**
     * Update an existing subscription by the given ID and the new data.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/PUT_Subscription
     *
     * @param  string $id
     * @param  array  $data
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function update(string $id, array $data): object
    {
        return $this->client->request('PUT', $this->client->getApiVersion().'/subscriptions/'.$id, [ 'json' => $data ])->toObject();
    }


    /**
     * Renew the subscription by the specified ID.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/PUT_RenewSubscription
     *
     * @param  string $id
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function renew(string $id): object
    {
        return $this->client->request('PUT', $this->client->getApiVersion().'/subscriptions/'.$id.'/renew', [])->toObject();
    }


    /**
     * Cancel the subscription by the specified ID.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/PUT_CancelSubscription
     *
     * @param  string $id
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function cancel(string $id): object
    {
        return $this->client->request('PUT', $this->client->getApiVersion().'/subscriptions/'.$id.'/cancel', [])->toObject();
    }
}

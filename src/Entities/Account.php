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


class Account extends Entity
{
    /**
     * Retrieves all the entities.
     *
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function all(): object
    {
        $toExclude = [
            'SequenceSetId',
            'TaxExemptEntityUseCode',
            'TotalDebitMemoBalance',
            'UnappliedCreditMemoAmount'
        ];

        $fields = $this->prepareFieldsQuery($this->fields(), $toExclude);

        return $this->query('select '.$fields.' from account')->toObject();
    }


    /**
     * Retrieves the desired account by his account ID on Zuora. If needed, set the $summary to true
     * to retrieve all the related informations about the user (default false).
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/GET_Account
     *
     * @param  string $id
     * @param  bool   $summary
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function get(string $id, bool $summary = false): object
    {
        return $this->client->request('GET', $this->client->getApiVersion().'/accounts/'.$id.($summary ? '/summary' : ''), [])->toObject();
    }


    /**
     * Creates a new account by the given data.
     * If the creation goes right, it returns an object with the "accountId" and "accountNumber" and other infos.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/POST_Account
     *
     * @param  array  $data
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function create(array $data): object
    {
        return $this->client->request('POST', $this->client->getApiVersion().'/accounts', [ 'json' => $data ])->toObject();
    }


    /**
     * Update an existing account by the given ID and the new data.
     * It will return "success: true" if all goes right.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/PUT_Account
     *
     * @param  string $id
     * @param  array  $data
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function update(string $id, array $data): object
    {
        return $this->client->request('PUT', $this->client->getApiVersion().'/accounts/'.$id, [ 'json' => $data ])->toObject();
    }


    /**
     * Deletes the existing account by the specified ID.
     * Pay attention: it needs the REAL OBJECT ID and not the account number!.
     *
     * @link   https://www.zuora.com/developer/api-reference/#operation/Object_DELETEAccount
     *
     * @param  string $id
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function delete(string $id): object
    {
        return $this->client->request('DELETE', $this->client->getApiVersion().'/object/account/'.$id, [])->toObject();
    }


    /**
     * Retrieves the fields detail of the account entity.
     *
     * @link   https://www.zuora.com/developer/api-reference/#tag/Describe
     *
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function fields(): object
    {
        $fields    = [];
        $response  = $this->client->request('GET', $this->client->getApiVersion().'/describe/Account', [])->toObject('fields')->field;

        foreach ($response as $rawField) {
            $fields[$rawField['name']] = [
                'name'     => $rawField['name'],
                'label'    => $rawField['name'],
                'custom'   => $rawField['custom'],
                'required' => $rawField['required'],
                'type'     => $rawField['type']
            ];
        }

        return (object) $fields;
    }


    /**
     * Retrieves the field details of the account entity by the given name.
     *
     * @param  string $field
     * @return object
     * @throws ZuoraApiException|GuzzleException
     */
    public function field(string $field): object
    {
        $fields = $this->fields();

        if (array_key_exists($field, (array) $fields))
            return (object) (((array)$this->fields())[$field]);

        return (object) ['success' => false, 'message' => 'Field ' . $field . ' not found'];
    }
}

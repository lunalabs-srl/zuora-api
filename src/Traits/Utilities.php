<?php
/**
 * This file is part of the Zuora API package.
 *
 * (c) Alessandro Orrù <alessandro.orru@aleostudio.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace LunaLabs\ZuoraApi\Traits;

// Package classes.
use LunaLabs\ZuoraApi\Exceptions\ZuoraApiException;
use LunaLabs\ZuoraApi\Http\Client;


trait Utilities
{
    /**
     * Prepares the string to pass to the ZOQL query with all the field names retrieved from the given fields array.
     * It is possible to pass, as second parameter, a set of field names to be excluded.
     *
     * @param  object $fields
     * @param  array  $excluded
     * @return string
     */
    public function prepareFieldsQuery(object $fields, array $excluded = []): string
    {
        return implode(',', array_diff(array_keys((array) $fields), $excluded));
    }
}
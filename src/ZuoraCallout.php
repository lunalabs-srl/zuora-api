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
use LunaLabs\ZuoraApi\Exceptions\ZuoraApiException;


class ZuoraCallout
{
    /**
     * The basic authentication credentials.
     *
     * @var string $username
     * @var string $password
     */
    private $username;
    private $password;

    const DEFAULT_USERNAME = 'zu0ra5tr0ngUSerN4m3';
    const DEFAULT_PASSWORD = 'zu0ra5tr0ngP4ssW0rD';

    /**
     * Custom log system to write the response data.
     * It needs a method "write()".
     *
     * @var object $log
     */
    private $log;


    /**
     * Zuora Callout constructor.
     *
     * @param array  $config   Zuora callout configuration.
     * @param object $log      Custom log system.
     */
    public function __construct(array $config = null, object $log = null)
    {
        // Checks if custom username and password are passed. Otherwise the default credentials will be used.
        if (isset($config['username']) && isset($config['password'])) {
            $this->username = $config['username'];
            $this->password = $config['password'];
        } else {
            $this->username = static::DEFAULT_USERNAME;
            $this->password = static::DEFAULT_PASSWORD;
        }

        // Sets the custom log system, if given.
        if (!is_null($log)) $this->log = $log;
    }


    /**
     * Performs a basic www authentication following the official RFC 7617.
     *
     * @link https://tools.ietf.org/html/rfc7617
     * @link https://www.php.net/manual/en/features.http-auth.php
     */
    private function checkAuthentication()
    {
        header('Cache-Control: no-cache, must-revalidate, max-age=0');

        $authenticated = (
            (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])) &&
            ($_SERVER['PHP_AUTH_USER'] == $this->username && $_SERVER['PHP_AUTH_PW'] == $this->password)
        );

        if (!$authenticated) {
            header('HTTP/1.1 401 Authorization Required');
            header('WWW-Authenticate: Basic realm="Access denied"');
            exit;
        }
    }


    /**
     * Returns the response as array.
     *
     * If set in the instance, a custom log system method "write()"
     * will be called to save the output.
     *
     * @return array
     * @throws ZuoraApiException
     */
    public function getResponse(): array
    {
        // Performs the basic www authentication check.
        $this->checkAuthentication();

        $attributes = [];

        // Adds to the response all the GET parameters found.
        foreach($_GET as $k => $v)
            $attributes[$k] = $v;

        // Checks if the Content-Type is application/json.
        $content_type = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        if (stripos($content_type, 'application/json') === false)
            throw new ZuoraApiException('Content-Type must be application/json');

        // Read the input stream and decode the given JSON.
        $bodyResponse = file_get_contents("php://input");
        $jsonElements = json_decode($bodyResponse, true);

        if (!is_array($jsonElements))
            throw new ZuoraApiException('Failed to decode JSON object');

        // Adds to the response all the elements found in the JSON.
        foreach ($jsonElements as $k => $v)
            $attributes[$k] = $v;

        // If an external logger is passed, the "write" method will be called.
        if (!is_null($this->log))
            $this->log->write($attributes);

        return $attributes;
    }
}

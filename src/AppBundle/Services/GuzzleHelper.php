<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Zaslavsky
 * Date: 27/11/17
 * Time: 16:05
 */

namespace AppBundle\Services;

use GuzzleHttp\Client;

/**
 * Class GuzzleHelper
 * @package AppBundle\Services
 */
class GuzzleHelper
{
    /**
     * Authorized method names
     */
    const AUTHORIZED_METHODS = [
        'head',
        'headAsync',
        'get',
        'getAsync',
        'post',
        'postAsync',
        'put',
        'putAsync',
        'delete',
        'deleteAsync',
        'options',
        'optionsAsync',
        'patch',
        'patchAsync',
    ];

    /**
     * @var Client
     */
    private $client;

    /**
     * GuzzleHelper constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return GuzzleHelper
     */
    public function setClient($client): GuzzleHelper
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @method \Psr\Http\Message\ResponseInterface head(string $uri, array $options)
     * @method \Psr\Http\Message\ResponseInterface headAsync(string $uri, array $options)
     * @method \Psr\Http\Message\ResponseInterface get(string $uri, array $options)
     * @method \Psr\Http\Message\ResponseInterface getAsync(string $uri, array $options)
     * @method \Psr\Http\Message\ResponseInterface post(string $uri, array $options)
     * @method \Psr\Http\Message\ResponseInterface postAsync(string $uri, array $options)
     * @method \Psr\Http\Message\ResponseInterface put(string $uri, array $options)
     * @method \Psr\Http\Message\ResponseInterface putAsync(string $uri, array $options)
     * @method \Psr\Http\Message\ResponseInterface delete(string $uri, array $options)
     * @method \Psr\Http\Message\ResponseInterface deleteAsync(string $uri, array $options)
     * @method \Psr\Http\Message\ResponseInterface options(string $uri, array $options)
     * @method \Psr\Http\Message\ResponseInterface optionsAsync(string $uri, array $options)
     * @method \Psr\Http\Message\ResponseInterface patch(string $uri, array $options)
     * @method \Psr\Http\Message\ResponseInterface patchAsync(string $uri, array $options)
     */
    public function __call(string $name, array $args)
    {
        if ( ! in_array($name, self::AUTHORIZED_METHODS)) {
            throw new \RuntimeException("Method {$name} does not exist in {$this->getClass()}");
        }
        if ( count($args) < 1 || ! is_string($args[0])) {
            throw new \InvalidArgumentException("String URI must be provided as first argument for method {$name}");
        }
        if ( count($args) > 2) {
            throw new \InvalidArgumentException("Too many arguments for method {$name}");
        }
        if (isset($args[1]) && ! is_array($args[1])) {
            throw new \InvalidArgumentException("Second argument for {$name}() must be an array or omitted.");
        }

        $method  = $name;
        $uri     = $args[0];
        $options = isset($args[1]) ? $args[1] : [];

        return $this->client->$method($uri, $options);
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return self::class;
    }
}
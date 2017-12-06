<?php
/**
 * Created by PhpStorm.
 * User: Benjamin Zaslavsky
 * Date: 28/11/17
 * Time: 16:09
 */

namespace AppBundle\Services;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ReqresCaller
 * @package AppBundle\Services
 */
class ReqresCaller
{
    /**
     * @var GuzzleHelper
     */
    private $helper;

    /**
     * Fake API base URI
     */
    const BASE_URI = 'https://reqres.in/api';

    /**
     * ReqresCaller constructor.
     *
     * @param GuzzleHelper $helper
     */
    public function __construct(GuzzleHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param $method
     * @param $resource
     * @param array $opts
     * @param bool $async
     *
     * @return mixed
     * @throws \Exception
     */
    private function callResource($method, $resource, $opts = [], $async = false)
    {
        $method = strtolower($method);
        if ($async) {
            $method .= 'Async';
        }
        if (0 !== strpos($resource, '/')) {
            $resource = '/'.$resource;
        }
        $url = self::BASE_URI.$resource;

        return $this->helper->$method($url, $opts);
    }

    /**
     * @param $resource
     * @param int $page
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    private function listResource(string $resource, int $page = 1): ResponseInterface
    {
        if (1 !== $page && ( ! is_int($page) || ! ctype_digit($page))) {
            throw new \InvalidArgumentException("First argument (page) must be a number if provided.");
        }
        $url = 1 === $page ? "/{$resource}" : "/{$resource}?page={$page}";

        return $this->callResource('get', $url);
    }

    /**
     * @param $resource
     * @param $id
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    private function getResource(string $resource, int $id): ResponseInterface
    {
        if ( ! is_int($id)) {
            if ( ! ctype_digit($id)) {
                throw new \InvalidArgumentException("Second argument (id) must be a number.");
            }
        }

        return $this->callResource('get', "/{$resource}/{$id}");
    }

    /**
     * @param string $resource
     * @param $data
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    private function postResource(string $resource, $data): ResponseInterface
    {
        $opts = is_string($data) ? ['body' => $data] : ['form_params' => $data];
        return $this->callResource('post', "/{$resource}", $opts);
    }

    /**
     * @param string $resource
     * @param int $id
     * @param $data
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    private function putResource(string $resource, int $id, $data): ResponseInterface
    {
        $opts = is_string($data) ? ['body' => $data] : ['form_params' => $data];
        return $this->callResource('put', "/{$resource}/{$id}", $opts);
    }

    /**
     * @param int $page
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function listUsers(int $page = 1): ResponseInterface
    {
        return $this->listResource('users', $page);
    }

    /**
     * @param $id
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function getSingleUser(int $id): ResponseInterface
    {
        return $this->getResource('users', $id);
    }

    /**
     * @param array $data
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function postUser(array $data = []): ResponseInterface
    {
        return $this->postResource('users', $data);
    }

    /**
     * @param int|string $id
     * @param array $data
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function updateUser($id, array $data = []): ResponseInterface
    {
        if ( ! is_int($id)) {
            if ( ! ctype_digit($id)) {
                throw new \InvalidArgumentException("Second argument (id) must be a number.");
            }
        }
        return $this->putResource('users', $id, $data);
    }

    /**
     * @param int $page
     *
     * @return ResponseInterface
     * @throws \Exception
     */
    public function listColors(int $page = 1): ResponseInterface
    {
        return $this->listResource('colors', $page);
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getColor(int $id): ResponseInterface
    {
        return $this->getResource('colors', $id);
    }
}
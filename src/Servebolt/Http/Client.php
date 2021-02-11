<?php

namespace Servebolt\Sdk\Http;

use Servebolt\Sdk\ConfigHelper;
use Servebolt\Sdk\Facades\Http;
use Servebolt\Sdk\Auth\ApiAuth;
use GuzzleHttp\Psr7\Response;

/**
 * Class Client
 * @package Servebolt\Sdk\Http
 */
class Client
{

    /**
     * The config class containing configuration values.
     *
     * @var ConfigHelper
     */
    private ConfigHelper $config;

    /**
     * @var string
     */
    private string $baseUri = 'https://api.servebolt.io/v1/';

    /**
     * An array containing the request headers.
     *
     * @var string[]
     */
    private array $headers = [];

    /**
     * @var Response
     */
    private Response $response;

    /**
     * Client constructor.
     * @param ApiAuth $authentication
     * @param ConfigHelper $config
     */
    public function __construct(ApiAuth $authentication, ConfigHelper $config)
    {
        $this->config = $config;
        if ($baseUri = $this->config->get('baseUri')) {
            $this->baseUri = $baseUri;
        }
        if ($this->throwExceptionsOnClientError()) {
            Http::enableClientExceptions();
        } else {
            Http::disableClientExceptions();
        }
        $this->headers = $authentication->getAuthHeaders();
    }

    private function throwExceptionsOnClientError() : bool
    {
        return filter_var(
            $this->config->get('throwExceptionsOnClientError', Http::shouldThrowClientExceptions()),
            FILTER_VALIDATE_BOOLEAN
        );
    }


    public function getResponseObject() : Response
    {
        return $this->response;
    }

    /**
     * Proxy method calls to PSR-7 response object (if present).
     *
     * @param $name
     * @param $arguments
     * @return false|mixed
     */
    public function __call($name, $arguments)
    {
        if (is_object($this->response)
            && is_a($this->response, '\\GuzzleHttp\\Psr7\\Response')
            && method_exists($this->response, $name)
        ) {
            return call_user_func_array([$this->response, $name], $arguments);
        }
        trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
    }

    /**
     * Get the JSON-data from the response object.
     *
     * @return object
     */
    public function getData() : object
    {
        return json_decode($this->response->getBody());
    }

    /**
     * @param string $uri
     * @param array $headers
     * @return Response
     */
    public function get(string $uri, array $headers = []) : Client
    {
        $this->response = Http::get($this->buildRequestURL($uri), $this->getRequestHeaders($headers));
        return $this;
    }

    /**
     * @param string $uri
     * @param array $body
     * @param array $headers
     * @return Response
     */
    public function post(string $uri, array $body = [], array $headers = []) : Client
    {
        $this->response = Http::post(
            $this->buildRequestURL($uri),
            $this->handleRequestBody($body),
            $this->getRequestHeaders($headers)
        );
        return $this;
    }

    /**
     * @param $body
     * @return false|string
     */
    private function handleRequestBody(array $body) : string
    {
        return json_encode($body);
    }

    /**
     * @param array $headers
     * @return array
     */
    public function getRequestHeaders(array $headers = []) : array
    {
        if (is_array($headers) && !empty($headers)) {
            return $this->headers + $headers;
        }
        return $this->headers;
    }

    /**
     * @param string $uri
     * @param bool $appendTrailingSlash
     * @return string
     */
    public function buildRequestURL(string $uri, $appendTrailingSlash = false) : string
    {
        return trim($this->baseUri, '/') . '/' . trim($uri, '/') . ($appendTrailingSlash ? '/' : '');
    }
}

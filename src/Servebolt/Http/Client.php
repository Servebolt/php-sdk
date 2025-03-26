<?php

namespace Servebolt\Sdk\Http;

use Servebolt\Sdk\ConfigHelper;
use Servebolt\Sdk\Facades\Http;
use Servebolt\Sdk\Auth\ApiAuth;
use Servebolt\Sdk\Exceptions\ServeboltInvalidJsonException;
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
    private $config;

    /**
     * @var string
     */
    private $baseUri = 'https://api.servebolt.io/v1/';

    /**
     * An array containing the request headers.
     *
     * @var string[]
     */
    private $headers = [];

    /**
     * @var Response
     */
    private $response;

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
        if (defined('SERVEBOLT_SDK_BASE_URI')) {
            $this->baseUri = SERVEBOLT_SDK_BASE_URI;
        }
        if ($this->config->get('verifySsl') === false) {
            Http::shouldVerifySsl(false);
        }
        if ($this->throwExceptionsOnClientError()) {
            Http::enableClientExceptions();
        } else {
            Http::disableClientExceptions();
        }
        $this->headers = $this->setDefaultHeaders($authentication);
    }

    private function userAgentString(): string
    {
        return 'ServeboltPhpSdk/' . SB_SDK_VERSION;
    }

    private function setDefaultHeaders($authentication): array
    {
        return array_merge(
            $authentication->getAuthHeaders(),
            [
                'User-Agent' => $this->userAgentString(),
            ]
        );
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
            && is_a($this->response, "\GuzzleHttp\Psr7\Response")
            && method_exists($this->response, $name)
        ) {
            return call_user_func_array([$this->response, $name], $arguments);
        }
        trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
    }

    /**
     * Get the JSON-data from the response body.
     *
     * @return object
     * @throws ServeboltInvalidJsonException
     */
    public function getDecodedBody() : object
    {
        if ($this->response->getBody()->getContents()) {
            $decodedBody = json_decode($this->response->getBody());
            if (json_last_error() == JSON_ERROR_NONE) {
                return (object) $decodedBody;
            }
            throw new ServeboltInvalidJsonException;
        }
        return (object) [];
    }

    /**
     * @param string $uri
     * @param array $headers
     * @return Client
     */
    public function delete(string $uri, array $headers = []) : Client
    {
        $this->response = Http::delete(
            $this->buildRequestURL($uri),
            $this->getRequestHeaders($headers)
        );
        return $this;
    }

    /**
     * @param string $uri
     * @param array $headers
     * @return Client
     */
    public function get(string $uri, array $headers = []) : Client
    {
        $this->response = Http::get(
            $this->buildRequestURL($uri),
            $this->getRequestHeaders($headers)
        );
        return $this;
    }

    /**
     * @param string $uri
     * @param string|null $body
     * @param array $headers
     * @return Client
     */
    public function post($uri, $body = null, $headers = []) : Client
    {
        $this->response = Http::post(
            $this->buildRequestURL($uri),
            $body,
            $this->getRequestHeaders($headers)
        );
        return $this;
    }

    /**
     * @param string $uri
     * @param array $body
     * @param array $headers
     * @return $this
     */
    public function postJson(string $uri, array $body = [], array $headers = []) : Client
    {
        $headers['Content-Type'] = 'application/json';
        $body = $this->handleJsonRequestBody($body);
        return $this->post($uri, $body, $headers);
    }

    /**
     * @param string $uri
     * @param array $body
     * @param array $headers
     * @return $this
     */
    public function patchJson(string $uri, array $body = [], array $headers = []) : Client
    {
        $headers['Content-Type'] = 'application/json';
        $body = $this->handleJsonRequestBody($body);
        return $this->patch($uri, $body, $headers);
    }

    /**
     * @param string $uri
     * @param string|null $body
     * @param array $headers
     * @return Client
     */
    public function patch($uri, $body = null, $headers = []) : Client
    {
        $this->response = Http::patch(
            $this->buildRequestURL($uri),
            $body,
            $this->getRequestHeaders($headers)
        );
        return $this;
    }

    /**
     * @param string $uri
     * @param array $body
     * @param array $headers
     * @return $this
     */
    public function putJson(string $uri, array $body = [], array $headers = []) : Client
    {
        $headers['Content-Type'] = 'application/json';
        $body = $this->handleJsonRequestBody($body);
        return $this->patch($uri, $body, $headers);
    }

    /**
     * @param string $uri
     * @param string|null $body
     * @param array $headers
     * @return Client
     */
    public function put( $uri, $body = null, $headers = []) : Client
    {
        $this->response = Http::put(
            $this->buildRequestURL($uri),
            $body,
            $this->getRequestHeaders($headers)
        );
        return $this;
    }

    private function handleJsonRequestBody(array $body) : string
    {
        return json_encode($body);
    }

    /**
     * @param string $uri
     * @param array $body
     * @param array $headers
     * @return Client
     */
    public function postFormData(string $uri, array $body = [], array $headers = []) : Client
    {
        $headers['Content-Type'] = 'application/x-www-form-urlencoded';
        $body = $this->handleFormRequestBody($body);
        return $this->post($uri, $body, $headers);
    }

    private function handleFormRequestBody(array $body) : string
    {
        return http_build_query($body, '', '&');
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

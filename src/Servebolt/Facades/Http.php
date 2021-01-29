<?php

namespace Servebolt\SDK\Facades;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Mockery;
use Mockery\MockInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Http
{
    private static Http $service;
    private Client $client;
    private MockInterface $mock;

    private function isMocked() : bool
    {
        return isset($this->mock);
    }

    private function mock() : MockInterface {
        if (!isset($this->mock)) {
            $this->mock = Mockery::mock('Http');
        }
        return $this->mock;
    }

    private function client() : Client
    {
        if (!isset($this->client)) {
            $this->client = new Client([]);
        }
        return $this->client;
    }

    public function request(string $method, string $uri, array $headers = []) : ResponseInterface
    {
        if ($this->isMocked()) {
            return $this->mock()->request($method, $uri, $headers);
        }
        $response = $this->client()->request($method, $uri, [
            'headers' => $headers,
        ]);
        return new Response(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase()
        );
    }

    private static function facade() : Http
    {
        if (!isset(self::$service)) {
            self::$service = new Http();
        }
        return self::$service;
    }

    public static function shouldReceive()
    {
        return self::facade()->mock()->shouldReceive(...func_get_args());
    }

    public static function send(RequestInterface $request) : ResponseInterface {
        $method = $request->getMethod();
        $uri = $request->getUri();
        $headers = $request->getHeaders();
        return self::facade()->request($method, $uri, $headers);
    }

    public static function get(string $uri, array $headers = []) : ResponseInterface {
        return self::send(new Request('GET', $uri, $headers));
    }

    public static function post(string $uri, array $headers = []) : ResponseInterface {
        return self::send(new Request('POST', $uri, $headers));
    }
}

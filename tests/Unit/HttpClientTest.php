<?php

namespace Servebolt\SDK\Tests;

use Servebolt\SDK\Client;
use Servebolt\SDK\Facades\Http;
use PHPUnit\Framework\TestCase;

class HttpClientTest extends TestCase
{

    public function testThatAuthenticationHeaderGetsSet()
    {
        $apiKey = 'foo';
        $authHeaders = ['Authorization' => 'Bearer ' . $apiKey];
        Http::shouldReceive('request')->once();
        $client = new Client(['apiKey' => $apiKey, 'authDriver' => 'apiKeys']);
        $this->assertEquals($client->httpClient->getRequestHeaders(), $authHeaders);
    }

    public function testThatBaseUriGetsSet()
    {
        $requestUri = 'https://example.com/';
        Http::shouldReceive('request')->once();
        $client = new Client(['apiKey' => 'foo', 'baseUri' => $requestUri]);
        $this->assertEquals($client->httpClient->buildRequestURL('foo'), $requestUri . 'foo');
    }
}

<?php

namespace Servebolt\SDK\Tests;

use Servebolt\SDK\Client;
use Servebolt\SDK\Facades\Http;
use PHPUnit\Framework\TestCase;
use DMS\PHPUnitExtensions\ArraySubset\Assert;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

class HttpClientTest extends TestCase
{

    use ArraySubsetAsserts;

    public function testThatAuthenticationHeaderGetsSet()
    {
        $apiToken = 'foo';
        $authHeaders = ['Authorization' => 'Bearer ' . $apiToken];
        Http::shouldReceive('Request')->once();
        $client = new Client(['apiKey' => $apiToken, 'authDriver' => 'apiKeys']);
        $this->assertArraySubset($authHeaders, $client->httpClient->getRequestHeaders());
    }

    public function testThatBaseUriGetsSet()
    {
        $requestUri = 'https://example.com/';
        Http::shouldReceive('Request')->once();
        $client = new Client(['apiKey' => 'foo', 'baseUri' => $requestUri]);
        $this->assertEquals($client->httpClient->buildRequestURL('foo'), $requestUri . 'foo');
    }
}

<?php

namespace Servebolt\Sdk\Tests;

use Servebolt\Sdk\Client;
use PHPUnit\Framework\TestCase;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;
use Servebolt\Sdk\Exceptions\ServeboltInvalidOrMissingAuthDriverException;

class HttpClientTest extends TestCase
{

    use ArraySubsetAsserts;

    public function testServeboltInvalidAuthDriverException()
    {
        $this->expectException(ServeboltInvalidOrMissingAuthDriverException::class);
        $this->expectExceptionMessage('Invalid or missing auth driver for client.');
        new Client([]); // Lacking apiToken
    }

    public function testThatAuthenticationHeaderGetsSet()
    {
        $apiToken = 'foo';
        $authHeaders = ['Authorization' => 'Bearer ' . $apiToken];
        $client = new Client(['apiToken' => $apiToken, 'authDriver' => 'apiToken']);
        $this->assertArraySubset($authHeaders, $client->httpClient->getRequestHeaders());
    }

    public function testThatBaseUriGetsSet()
    {
        $requestUri = 'https://example.com/';
        $client = new Client(['apiToken' => 'foo', 'baseUri' => $requestUri]);
        $this->assertEquals($client->httpClient->buildRequestURL('foo'), $requestUri . 'foo');
    }
}

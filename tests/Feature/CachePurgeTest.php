<?php

namespace Servebolt\Sdk\Tests;

use Servebolt\Sdk\Client;
use GuzzleHttp\Psr7\Response;
use Servebolt\Sdk\Facades\Http;
use PHPUnit\Framework\TestCase;
use Servebolt\Sdk\Response as ServeboltResponse;

/**
 * Class CachePurgeTest
 * @package Servebolt\Sdk\Tests
 */
class CachePurgeTest extends TestCase
{
    /**
     * @var int The Servebolt environment Id.
     */
    private $environmentId = 123;

    /**
     * @var string The API URL.
     */
    private $apiBaseUri = 'https://api.servebolt.io/v1/';

    public function testThatWeCanConfigureResponseObjectTypes()
    {
        $responseObjectTypes = [
            'decodedBody' => \stdClass::class,
            'psr7' => Response::class,
            'customResponse' => ServeboltResponse::class,
        ];
        foreach ($responseObjectTypes as $responseObjectType => $responseObjectTypeClass) {
            $testUrl = $this->apiBaseUri . 'environments/' . $this->environmentId . '/purge_cache';
            Http::shouldReceive('request')->withSomeOfArgs('POST', $testUrl)
                ->once()->andReturn(new Response(200));
            $client = new Client([
                'apiToken' => 'foo',
                'responseObjectType' => $responseObjectType,
            ]);
            $files = ['https://domain.com/url-1', 'https://domain.com/url-2'];
            $response = $client->environment->purgeCache($this->environmentId, $files);
            $this->assertInstanceOf($responseObjectTypeClass, $response);
        }
    }

    public function testThatCdnCachePurges()
    {
        $testUrl = $this->apiBaseUri . 'environments/' . $this->environmentId . '/purge_cache';
        Http::shouldReceive('request')->withSomeOfArgs('POST', $testUrl)
            ->once()->andReturn(new Response(200));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $hostnames = ['domain.com', 'domain2.com'];
        $response = $client->environment->purgeCdnCache($this->environmentId, $hostnames);
        $this->assertInstanceOf(ServeboltResponse::class, $response);
        $this->assertTrue($response->wasSuccessful());
    }

    public function testThatCachePurges()
    {
        $testUrl = $this->apiBaseUri . 'environments/' . $this->environmentId . '/purge_cache';
        Http::shouldReceive('request')->withSomeOfArgs('POST', $testUrl)
            ->once()->andReturn(new Response(200));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $files = ['https://domain.com/url-1', 'https://domain.com/url-2'];
        $response = $client->environment($this->environmentId)->purgeCache($files);
        $this->assertInstanceOf(ServeboltResponse::class, $response);
        $this->assertTrue($response->wasSuccessful());
    }

    public function testThatCachePurgesPassingEnvironmentIdThroughPurgeMethod()
    {
        $testUrl = $this->apiBaseUri . 'environments/' . $this->environmentId . '/purge_cache';
        Http::shouldReceive('request')->withSomeOfArgs('POST', $testUrl)
            ->once()->andReturn(new Response(200));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $files = ['https://domain.com/url-1', 'https://domain.com/url-2'];
        $response = $client->environment->purgeCache($this->environmentId, $files);
        $this->assertTrue($response->wasSuccessful());
    }

    public function testThatCachePurgesEvenThoMultipleEnvironmentIdsArePresent()
    {
        $testUrl = $this->apiBaseUri . 'environments/' . $this->environmentId . '/purge_cache';
        Http::shouldReceive('request')->withSomeOfArgs('POST', $testUrl)
            ->once()->andReturn(new Response(200));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $files = ['https://domain.com/url-1', 'https://domain.com/url-2'];
        $response = $client->environment(69)->purgeCache($this->environmentId, $files);
        $this->assertTrue($response->wasSuccessful());
    }

    public function testThatCachePurgeFails()
    {
        $testUrl = $this->apiBaseUri . 'environments/' . $this->environmentId . '/purge_cache';
        Http::shouldReceive('request')->withSomeOfArgs('POST', $testUrl)
            ->once()->andReturn(new Response(422));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $files = ['https://domain.com/url-1', 'https://domain.com/url-2'];
        $response = $client->environment->purgeCache($this->environmentId, $files);
        $this->assertFalse($response->wasSuccessful());
    }
}

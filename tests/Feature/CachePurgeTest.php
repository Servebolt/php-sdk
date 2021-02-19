<?php

namespace Servebolt\Sdk\Tests;

use Servebolt\Sdk\Client;
use GuzzleHttp\Psr7\Response;
use Servebolt\Sdk\Facades\Http;
use PHPUnit\Framework\TestCase;
use Servebolt\Sdk\Response as ServeboltResponse;

class CachePurgeTest extends TestCase
{
    private int $environmentId = 123;

    private string $apiBaseUri = 'https://api.servebolt.io/v1/';

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
                ->once()->andReturn(new Response(200, [], json_encode(['success' => true])));
            $client = new Client([
                'apiToken' => 'foo',
                'responseObjectType' => $responseObjectType,
            ]);
            $files = ['https://domain.com/url-1', 'https://domain.com/url-2'];
            $response = $client->environment->purgeCache($this->environmentId, $files);
            $this->assertInstanceOf($responseObjectTypeClass, $response);
        }
    }

    public function testThatCachePurges()
    {
        $testUrl = $this->apiBaseUri . 'environments/' . $this->environmentId . '/purge_cache';
        Http::shouldReceive('request')->withSomeOfArgs('POST', $testUrl)
            ->once()->andReturn(new Response(200, [], json_encode(['success' => true])));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $files = ['https://domain.com/url-1', 'https://domain.com/url-2'];
        $response = $client->environment($this->environmentId)->purgeCache($files);
        $this->assertTrue($response->wasSuccessful());
    }

    public function testThatCachePurgesPassingEnvironmentIdThroughPurgeMethod()
    {
        $testUrl = $this->apiBaseUri . 'environments/' . $this->environmentId . '/purge_cache';
        Http::shouldReceive('request')->withSomeOfArgs('POST', $testUrl)
            ->once()->andReturn(new Response(200, [], json_encode(['success' => true])));
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
            ->once()->andReturn(new Response(200, [], json_encode(['success' => true])));
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
            ->once()->andReturn(new Response(200, [], json_encode(['success' => false])));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $files = ['https://domain.com/url-1', 'https://domain.com/url-2'];
        $response = $client->environment->purgeCache($this->environmentId, $files);
        $this->assertFalse($response->wasSuccessful());
    }
}

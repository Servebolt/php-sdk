<?php

namespace Servebolt\Sdk\Tests;

use Servebolt\SDK\Client;
use GuzzleHttp\Psr7\Response;
use Servebolt\SDK\Facades\Http;
use PHPUnit\Framework\TestCase;

class CachePurgeTest extends TestCase
{
    private int $environmentId = 123;

    public function testThatCachePurges()
    {
        $testUrl = 'https://api.servebolt.io/v1/environments/' . $this->environmentId . '/purge_cache';
        Http::shouldReceive('Request')->withSomeOfArgs('POST', $testUrl)
            ->once()->andReturn(new Response(200, [], json_encode(['success' => true])));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $files = ['https://domain.com/url-1', 'https://domain.com/url-2'];
        $this->assertTrue($client->environment->setEnvironment($this->environmentId)->cache->purge($files, []));
    }

    public function testThatCachePurgeFails()
    {
        $testUrl = 'https://api.servebolt.io/v1/environments/' . $this->environmentId . '/purge_cache';
        Http::shouldReceive('Request')->withSomeOfArgs('POST', $testUrl)
            ->once()->andReturn(new Response(200, [], json_encode(['success' => false])));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $files = ['https://domain.com/url-1', 'https://domain.com/url-2'];
        $this->assertFalse($client->environment->setEnvironment($this->environmentId)->cache->purge($files, []));
    }
}

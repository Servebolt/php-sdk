<?php

namespace Servebolt\Sdk\Tests;

use GuzzleHttp\Psr7\Response;
use Servebolt\Sdk\Facades\Http;
use PHPUnit\Framework\TestCase;

class HttpFacadeTest extends TestCase
{
    public function testHttpMockRequestWithServerError()
    {
        Http::shouldReceive('request')->withSomeOfArgs('POST', 'http://example.com/index.php')
            ->once()->andReturn(new Response(501, []));
        $response = Http::post('http://example.com/index.php');
        $this->assertEquals(501, $response->getStatusCode());
    }

    public function testHttpMockRequestWithSuccess()
    {
        Http::shouldReceive('request')->withSomeOfArgs('GET', 'http://example.com/index.php')
            ->once()->andReturn(new Response(200, []));
        $response = Http::get('http://example.com/index.php');
        $this->assertEquals(200, $response->getStatusCode());
    }
}

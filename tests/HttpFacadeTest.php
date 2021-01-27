<?php

namespace Servebolt\SDL\Tests;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Servebolt\SDK\Facades\Http;

class HttpFacadeTest extends TestCase
{

    public function testHttpGet()
    {
        $this->markTestSkipped('do not make real HTTP requests during tests!');
        $response = Http::get('http://sb.local/index.php', []);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testWithMocks()
    {
        Http::shouldReceive('request')->withSomeOfArgs('GET', 'http://example.com/index.php')
            ->once()->andReturn(new Response(200, []));
        Http::shouldReceive('request')->withSomeOfArgs('POST', 'http://example.com/index.php')
            ->once()->andReturn(new Response(501, []));
        $response = Http::get('http://example.com/index.php', []);
        $this->assertEquals(200, $response->getStatusCode());
        $response = Http::post('http://example.com/index.php', []);
        $this->assertEquals(501, $response->getStatusCode());
    }
}

<?php

namespace Servebolt\Sdk\Tests;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Servebolt\Sdk\Client;
use Servebolt\Sdk\Facades\Http;

class EnvironmentEndpointTest extends TestCase
{
    /**
     * @var string The API URL.
     */
    private $apiBaseUri = 'https://api.servebolt.io/v1/';

    public function testEnvironmentGet()
    {
        $id = 69;
        $testUrl = $this->apiBaseUri . 'environments/' . $id;
        $item = $this->getEnvironmentData($id);
        Http::shouldReceive('request')->withSomeOfArgs('GET', $testUrl)
            ->once()->andReturn(new Response(200, [], json_encode(['data' => $item])));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $response = $client->environment->get($id);
        $this->assertTrue($response->wasSuccessful());
        $this->assertIsObject($response->getFirstResultItem());
        $this->assertEquals($item, $response->getFirstResultItem());
        $this->assertEquals(69, $response->getFirstResultItem()->id);
        $this->assertEquals('environments', $response->getFirstResultItem()->type);
        $this->assertEquals('Testing', $response->getFirstResultItem()->attributes->title);
        $this->assertEquals('UTC', $response->getFirstResultItem()->attributes->phpTimezone);
        $this->assertEquals('all', $response->getFirstResultItem()->attributes->cacheMode);
        $this->assertEquals(1, $response->getFirstResultItem()->attributes->ssh);
        $this->assertEquals('both', $response->getFirstResultItem()->attributes->environmentInfoFile);
    }

    public function testEnvironmentUpdate()
    {
        $id = 69;
        $testUrl = $this->apiBaseUri . 'environments/' . $id;
        $item = $this->getEnvironmentData();
        Http::shouldReceive('request')->withSomeOfArgs('PATCH', $testUrl)
            ->once()->andReturn(new Response(200, [], json_encode(['data' => $item])));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $response = $client->environment->update($id, [
            'attributes' => [
                'cacheMode' => 'all',
            ],
        ]);
        $this->assertTrue($response->wasSuccessful());
        $this->assertEquals($item, $response->getFirstResultItem());
    }

    private function getEnvironmentData($includeId = false, $asArray = false)
    {
        $data = (object) [
            'type' => 'environments',
            'attributes' => (object) [
                'title' => 'Testing',
                'phpTimezone' => 'UTC',
                'phpMemoryLimit' => 128,
                'htaccessRoot' => 'public',
                'cacheMode' => 'all',
                'ssh' => 1,
                'sftp' => 1,
                'environmentInfoFile' => 'both',
            ],
            'relationships' => (object) [
                'bolt' => (object) [
                    'data' => (object) [
                        'type' => 'bolts',
                        'id' => 1234,
                    ]
                ]
            ],
            'links' => (object) [
                'self' => 'https://api-sbtest.servebolt.io/v1/environments/2686',
            ]
        ];

        if ($includeId) {
            $data->id = is_numeric($includeId) ? $includeId : 69;
        }
        if ($asArray) {
            $data = $this->toArray($data);
        }
        return $data;
    }

    /**
     * Convert an object to an array recursively.
     *
     * @param object $object
     * @return array
     */
    private function toArray($object): array
    {
        return json_decode(json_encode($object), true);
    }

    /**
     * Convert an array to an object recursively.
     *
     * @param array $array
     * @return object
     */
    private function toObject($array): object
    {
        return json_decode(json_encode($array));
    }
}

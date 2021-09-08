<?php

namespace Servebolt\Sdk\Tests;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Servebolt\Sdk\Client;
use Servebolt\Sdk\Facades\Http;

class CronEndpointTest extends TestCase
{
    /**
     * @var string The API URL.
     */
    private $apiBaseUri = 'https://api.servebolt.io/v1/';

    public function testCronjobList()
    {
        $testUrl = $this->apiBaseUri . 'cronjobs';
        $items = [$this->getCronjobData(69), $this->getCronjobData(70), $this->getCronjobData(71)];
        Http::shouldReceive('request')->withSomeOfArgs('GET', $testUrl)
            ->once()->andReturn(new Response(200, [], json_encode(['data' => $items])));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $response = $client->cron->list();
        $this->assertTrue($response->wasSuccessful());
        $this->assertEquals($items, $response->getResultItems());
        $this->assertEquals($items[0], $response->getResultItems()[0]);
        $this->assertEquals($items[1], $response->getResultItems()[1]);
        $this->assertEquals($items[2], $response->getResultItems()[2]);
        $this->assertCount(3, $response->getResultItems());
    }

    public function testCronjobCreate()
    {
        $id = 69;
        $testUrl = $this->apiBaseUri . 'cronjobs';
        $creationData = $this->getCronjobData(null, true);
        $responseData = $this->toObject($creationData);
        $responseData->id = $id;
        Http::shouldReceive('request')->withSomeOfArgs('POST', $testUrl)
            ->once()->andReturn(new Response(201, [], json_encode(['data' => $responseData])));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $response = $client->cron->create($creationData);
        $this->assertTrue($response->wasSuccessful());
        $item = $response->getFirstResultItem();
        $this->assertEquals($responseData, $item);
        $this->assertEquals($id, $item->id);
        $this->assertEquals($creationData['type'], $item->type);
        $this->assertEquals($creationData['attributes']['comment'], $item->attributes->comment);
        $this->assertEquals($creationData['relationships']['environment']['data']['type'], $item->relationships->environment->data->type);
    }

    public function testCronjobGet()
    {
        $id = 69;
        $testUrl = $this->apiBaseUri . 'cronjobs/' . $id;
        $item = $this->getCronjobData($id);
        Http::shouldReceive('request')->withSomeOfArgs('GET', $testUrl)
            ->once()->andReturn(new Response(200, [], json_encode(['data' => $item])));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $response = $client->cron->get($id);
        $this->assertTrue($response->wasSuccessful());
        $this->assertIsObject($response->getFirstResultItem());
        $this->assertEquals($item, $response->getFirstResultItem());
        $this->assertEquals(69, $response->getFirstResultItem()->id);
        $this->assertEquals('cronjobs', $response->getFirstResultItem()->type);
        $this->assertEquals(1, $response->getFirstResultItem()->attributes->enabled);
        $this->assertEquals('ls ./', $response->getFirstResultItem()->attributes->command);
        $this->assertEquals('This is a comment', $response->getFirstResultItem()->attributes->comment);
        $this->assertEquals('* * * * 1', $response->getFirstResultItem()->attributes->schedule);
        $this->assertEquals('errors', $response->getFirstResultItem()->attributes->notifications);
    }

    public function testCronjobDelete()
    {
        $id = 69;
        $testUrl = $this->apiBaseUri . 'cronjobs/' . $id;
        Http::shouldReceive('request')->withSomeOfArgs('DELETE', $testUrl)
            ->once()->andReturn(new Response(204));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $response = $client->cron->delete($id);
        $this->assertTrue($response->wasSuccessful());
    }

    public function testCronjobUpdate()
    {
        $id = 69;
        $testUrl = $this->apiBaseUri . 'cronjobs/' . $id;
        $item = $this->getCronjobData();
        Http::shouldReceive('request')->withSomeOfArgs('PATCH', $testUrl)
            ->once()->andReturn(new Response(200, [], json_encode(['data' => $item])));
        $client = new Client([
            'apiToken' => 'foo',
        ]);
        $response = $client->cron->update($id, [
            'attributes' => [
                'enabled' => 1,
                'comment' => 'This is a comment',
            ],
        ]);
        $this->assertTrue($response->wasSuccessful());
        $this->assertEquals($item, $response->getFirstResultItem());
    }

    private function getCronjobData($includeId = false, $asArray = false)
    {
        $data = (object) [
            'type' => 'cronjobs',
            'attributes' => (object) [
                'enabled' => 1,
                'command' => 'ls ./',
                'comment' => 'This is a comment',
                'schedule' => '* * * * 1',
                'notifications' => 'errors',
            ],
            'relationships' => (object) [
                'environment' => (object) [
                    'data' => (object) [
                        'type' => 'environments',
                        'id' => 2368,
                    ]
                ]
            ],
            'links' => (object) [
                'related' => 'https://api-sbtest.servebolt.io/v1/environments/2686',
                'data' => (object) [
                    'type' => 'environments',
                    'id' => 2368,
                ]
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

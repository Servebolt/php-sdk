<?php

namespace Servebolt\Sdk\Tests;

use Servebolt\Sdk\Response;
use PHPUnit\Framework\TestCase;

class ResponseObjectTest extends TestCase
{

    public function testResponseWithErrorMessages()
    {
        $errorMessages = [
            (object) [
                'title' => 'Invalid input',
                'detail' => 'Invalid URL www.servebolt.nl.',
                'code' => 1115,
                'source' => (object) []
            ],
            (object) [
                'title' => 'Invalid input',
                'detail' => 'Invalid URL www.servebolt.nl.',
                'code' => 1115,
                'source' => (object) []
            ],
        ];
        $responseObject = new Response((object)[
            'errors' => $errorMessages,
        ], 422);
        $this->assertFalse($responseObject->wasSuccessful());
        $this->assertTrue($responseObject->hasErrors());
        $this->assertEquals($errorMessages, $responseObject->getErrors());
        $this->assertEquals(current($errorMessages), $responseObject->getFirstError());
        $this->assertEquals('Invalid input', $responseObject->getFirstError()->title);
        $this->assertEquals('Invalid input', $responseObject->getFirstErrorMessage());
        $this->assertEquals('Invalid URL www.servebolt.nl.', $responseObject->getFirstError()->detail);
        $this->assertEquals('1115', $responseObject->getFirstError()->code);
        $this->assertEquals((object) [], $responseObject->getFirstError()->source);
    }

    public function testResponseWithMessages()
    {
        $messages = [
            (object) [
                'message' => 'This is a notification about something',
            ],
            (object) [
                'message' => 'This is a notification about something else',
            ],
        ];
        $responseObject = new Response((object)[
            'messages' => $messages,
        ], 422);
        $this->assertTrue($responseObject->hasMessages());
        $this->assertEquals($messages, $responseObject->getMessages());
        $this->assertEquals(current($messages), $responseObject->getFirstMessage());
        $this->assertEquals('This is a notification about something', $responseObject->getFirstMessageString());
        $this->assertEquals('This is a notification about something', $responseObject->getFirstMessage()->message);
    }

    public function testResponseStatusCode()
    {
        $responseObject = new Response(null, 418);
        $this->assertEquals(418, $responseObject->getStatusCode());
    }

    public function testSuccessResponseWithData()
    {
        $testItems = $this->getCronjobTestItems();
        $responseObject = new Response((object)[
            'data' => $testItems,
        ], 200);
        $this->assertTrue($responseObject->wasSuccessful());
        $this->assertTrue($responseObject->hasMultiple());
        $this->assertTrue($responseObject->hasResult());
        $this->assertFalse($responseObject->hasMessages());
        $this->assertFalse($responseObject->hasErrors());
        $this->assertEquals($responseObject->getResult(), $testItems);
        $this->assertEquals($responseObject->getFirstResultItem(), current($testItems));
    }

    public function testSuccessResponseWithoutData()
    {
        $responseObject = new Response(null, 200);
        $this->assertTrue($responseObject->wasSuccessful());
        $this->assertFalse($responseObject->hasMultiple());
        $this->assertFalse($responseObject->hasResult());
        $this->assertFalse($responseObject->hasMessages());
        $this->assertFalse($responseObject->hasErrors());
        $this->assertNull($responseObject->getResult());
        $this->assertNull($responseObject->getFirstResultItem());
    }

    public function testAccessToPropertiesOnCronJobItem()
    {
        $responseObject = new Response((object) [
            'data' => $this->getCronjobTestItems(),
        ], 200, CronJob::class);
        $firstItem = $responseObject->getFirstResultItem();
        $this->assertIsObject($firstItem);
        $this->assertEquals(90, $firstItem->id);
        $this->assertEquals(2368, $firstItem->relationships->environment->data->id);
        $this->assertEquals(true, $firstItem->attributes->enabled);
        $this->assertEquals('ls ./', $firstItem->attributes->command);
        $this->assertEquals('This is a comment', $firstItem->attributes->comment);
        $this->assertEquals('* * * * 1', $firstItem->attributes->schedule);
        $this->assertEquals('errors', $firstItem->attributes->notifications);
    }

    private function getCronjobTestItems(): array
    {
        return [
            (object) [
                'type' => 'cronjobs',
                'id' => 90,
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
            ],
            (object) [
                'type' => 'cronjobs',
                'id' => 91,
                'attributes' => (object) [
                    'enabled' => 1,
                    'command' => 'ls ./',
                    'comment' => 'This is a cron job created using the PHP SDK',
                    'schedule' => '* * * * *',
                    'notifications' => 'none',
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
            ],
        ];
    }
}

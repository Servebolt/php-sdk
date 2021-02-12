<?php

namespace Servebolt\Sdk\Tests;

use Servebolt\Sdk\Models\CronJob;
use Servebolt\Sdk\Response;
use PHPUnit\Framework\TestCase;

class ResponseObjectTest extends TestCase
{

    public function testResponseWithErrorMessages()
    {
        $errorMessages = [
            (object) [
                'message' => 'The foo field is required.',
                'field' => 'foo',
            ],
            (object) [
                'message' => 'The bar field is required.',
                'field' => 'bar',
            ],
        ];
        $responseObject = new Response((object)[
            'success' => false,
            'errors' => $errorMessages,
        ]);
        $this->assertFalse($responseObject->wasSuccessful());
        $this->assertTrue($responseObject->hasErrors());
        $this->assertEquals($errorMessages, $responseObject->getErrors());
        $this->assertEquals(current($errorMessages), $responseObject->getFirstError());
        $this->assertEquals('The foo field is required.', $responseObject->getFirstError()->message);
        $this->assertEquals('foo', $responseObject->getFirstError()->field);
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
            'success' => false,
            'messages' => $messages,
        ]);
        $this->assertTrue($responseObject->hasMessages());
        $this->assertEquals($messages, $responseObject->getMessages());
        $this->assertEquals(current($messages), $responseObject->getFirstMessage());
        $this->assertEquals('This is a notification about something', $responseObject->getFirstMessage()->message);
    }

    public function testSuccessResponseWithData()
    {
        $responseObject = new Response((object)[
            'result' => $this->getTestItems(),
            'success' => true,
        ]);
        $this->assertTrue($responseObject->wasSuccessful());
        $this->assertTrue($responseObject->hasMultiple());
        $this->assertTrue($responseObject->hasResult());
        $this->assertFalse($responseObject->hasMessages());
        $this->assertFalse($responseObject->hasErrors());
        $this->assertEquals($responseObject->getResult(), $this->getTestItems());
        $this->assertEquals($responseObject->getFirstItem(), current($this->getTestItems()));
    }

    public function testSuccessResponseWithoutData()
    {
        $responseObject = new Response((object)[
            'success' => true,
        ]);
        $this->assertTrue($responseObject->wasSuccessful());
        $this->assertFalse($responseObject->hasMultiple());
        $this->assertFalse($responseObject->hasResult());
        $this->assertFalse($responseObject->hasMessages());
        $this->assertFalse($responseObject->hasErrors());
        $this->assertNull($responseObject->getResult());
        $this->assertNull($responseObject->getFirstItem());
    }

    public function testAccessToPropertiesOnCronJobItem()
    {
        $responseObject = new Response((object) [
            'result' => $this->getTestItems(),
            'success' => true,
        ], CronJob::class);
        $firstItem = $responseObject->getFirstItem();
        $this->assertIsObject($firstItem);
        $this->assertEquals(90, $firstItem->id);
        $this->assertEquals(2368, $firstItem->environmentId);
        $this->assertEquals(true, $firstItem->enabled);
        $this->assertEquals('cd ./', $firstItem->command);
        $this->assertEquals('This is a comment', $firstItem->comment);
        $this->assertEquals('* * * * 1', $firstItem->schedule);
        $this->assertEquals('errors', $firstItem->notifications);
    }

    private function getTestItems() : array
    {
        return [
            (object) [
                'id' => 90,
                'environmentId' => 2368,
                'enabled' => 1,
                'command' => 'cd ./',
                'comment' => 'This is a comment',
                'schedule' => '* * * * 1',
                'notifications' => 'errors',
            ],
            (object) [
                'id' => 91,
                'environmentId' => 2368,
                'enabled' => 1,
                'command' => 'ls ./',
                'comment' => '',
                'schedule' => '* * * * *',
                'notifications' => 'none',
            ],
        ];
    }
}

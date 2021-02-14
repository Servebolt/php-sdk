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
        ], 400);
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
        ], 400);
        $this->assertTrue($responseObject->hasMessages());
        $this->assertEquals($messages, $responseObject->getMessages());
        $this->assertEquals(current($messages), $responseObject->getFirstMessage());
        $this->assertEquals('This is a notification about something', $responseObject->getFirstMessage()->message);
    }

    public function testResponseStatusCode()
    {
        $responseObject = new Response((object)[
            'success' => false
        ], 418);
        $this->assertEquals(418, $responseObject->getStatusCode());
    }

    public function testSuccessResponseWithData()
    {
        $responseObject = new Response((object)[
            'result' => $this->getTestItems(),
            'success' => true,
        ], 200);
        $this->assertTrue($responseObject->wasSuccessful());
        $this->assertTrue($responseObject->hasMultiple());
        $this->assertTrue($responseObject->hasResult());
        $this->assertFalse($responseObject->hasMessages());
        $this->assertFalse($responseObject->hasErrors());
        $this->assertEquals($responseObject->getResult(), $this->getTestItems());
        $this->assertEquals($responseObject->getFirstResultItem(), current($this->getTestItems()));
    }

    public function testSuccessResponseWithoutData()
    {
        $responseObject = new Response((object)[
            'success' => true,
        ], 200);
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
            'result' => $this->getTestItems(),
            'success' => true,
        ], 200, CronJob::class);
        $firstItem = $responseObject->getFirstResultItem();
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

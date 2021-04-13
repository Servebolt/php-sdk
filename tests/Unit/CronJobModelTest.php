<?php


namespace Unit;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Servebolt\Sdk\Client;
use Servebolt\Sdk\Facades\Http;
use Servebolt\Sdk\Models\CronJob;
use Servebolt\Sdk\Exceptions\ServeboltInvalidModelDataException;

class CronJobModelTest extends TestCase
{

    public function testCronJobModelCreation()
    {
        $modelData = [
            'environmentId' => 69,
            'schedule' => '* * * * *',
            'command' => 'ls ./',
            'comment' => 'A string'
        ];
        $fullModelData = $modelData + [
            'id' => 1,
            'enabled' => true,
            'notifications' => 'all',
        ];
        Http::shouldReceive('request')->once()->andReturn(new Response(201, [], json_encode((object) [
            'success' => true,
            'result' => (object) $fullModelData,
        ])));
        $model = new CronJob($modelData);
        $client = new Client(['apiToken' => 'foo']);
        $response = $model->persist($client->cron);
        $this->assertEquals($fullModelData, (array) $response->getFirstResultItem());
        $this->assertTrue($response->wasSuccessful());
    }

    /*
     * This test is WIP due to the endpoint-binding of models is not done yet.
    public function testCronJobModelCreationWithoutClientReference()
    {
        $modelData = [
            'environmentId' => 69,
            'schedule' => '* * * * *',
            'command' => 'ls ./',
            'comment' => 'A string'
        ];
        $fullModelData = $modelData + [
                'id' => 1,
                'enabled' => true,
                'notifications' => 'all',
            ];
        Http::shouldReceive('request')->once()->andReturn(new Response(201, [], json_encode((object) [
            'success' => true,
            'result' => (object) $fullModelData,
        ])));
        new Client(['apiToken' => 'foo']);
        $model = new CronJob($modelData);
        $response = $model->persist();
        $this->assertEquals($fullModelData, (array) $response->getFirstResultItem());
        $this->assertTrue($response->wasSuccessful());
    }
    */

    /*
    public function testCronJobModelReplace()
    {
    }
    */

    /*
    public function testCronJobModelUpdate()
    {
    }
    */

    /*
    public function testCronJobModelDelete()
    {
    }
    */

    public function testCronJobModelProperties()
    {
        $model = new CronJob([
            'environmentId' => 69,
            'schedule' => '* * * * *',
            'command' => 'ls ./',
        ]);
        $this->assertEquals([
            'id',
            'environmentId',
            'schedule',
            'command',
            'comment',
            'enabled',
            'notifications',
        ], $model->getModelProperties());

        $this->assertEquals([
            'environmentId',
            'schedule',
            'command',
        ], $model->getRequiredPropertiesOnCreation());

        $this->assertEquals([
            'enabled' => 'boolean',
        ], $model->getCasts());
    }

    public function testCronJobModelHydrateExceptionOnInvalidData()
    {
        $this->expectException(ServeboltInvalidModelDataException::class);
        $this->expectExceptionMessage('Model is missing field environmentId');
        new CronJob([
            'schedule' => '* * * * *',
            'command' => 'ls ./',
        ], false, true);
    }

    public function testCronJobModelSuccessfulHydration()
    {
        try {
            $model = new CronJob([
                'environmentId' => 69,
                'schedule' => '* * * * *',
                'command' => 'ls ./',
                'foo' => 'bar', // Gets ignored
            ]);
            $this->assertTrue($model->isValid());
            $this->assertTrue($model->isHydrated());
            $this->assertFalse($model->isPersisted());
        } catch (\Exception $e) {
            $this->fail($e->getMessage());
        }
    }
}

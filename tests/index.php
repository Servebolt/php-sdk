<?php

// phpcs:ignoreFile
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new Servebolt\Sdk\Client([
    'verifySsl' => false,
    'apiToken' => $_ENV['API_TOKEN'],
    'baseUri' => $_ENV['BASE_URI'], // Default: https://api.servebolt.io/v1/
    'authDriver' => $_ENV['AUTH_DRIVER'], // Default: apiToken
    'returnPsr7Response' => false, // Default: false
    'throwExceptionsOnClientError' => false, // Default: true
    'throwExceptionsOnServerError' => true, // Default: false
]);

function domain() : string
{
    return $_ENV['SITE_DOMAIN'];
}

$environmentId = $_ENV['ENV_ID'];

$cronJobId = 178;
$cronJobData = [
    'type' => 'cronjobs',
    'attributes' => [
        'enabled' => 1,
        'command' => 'ls ./',
        'comment' => 'This is a cron job created using the PHP SDK',
        'schedule' => '* * * * *',
        'notifications' => 'none',
    ],
    'relationships' => [
        'environment' => [
            'data' => [
                'type' => 'environments',
                'id' => $environmentId,
            ]
        ]
    ],
    'links' => [
        'related' => 'https://api-sbtest.servebolt.io/v1/environments/2686',
        'data' => [
            'type' => 'environments',
            'id' => $environmentId,
        ]
    ]
];

$cronJobUpdateData = [
    'attributes' => [
        'comment' => 'This is a cron job that was updated using the PHP SDK',
    ],
];

function createCronJob($cronJobData, $client) {
    try {
        $response = $client->cron->create($cronJobData);
        var_dump($response->wasSuccessful());
    } catch (\Servebolt\Sdk\Exceptions\ServeboltHttpClientException $e) {
        echo '<pre>';
        print_r($e->getDecodeMessage());
        die;
    }
}
//createCronJob($cronJobData, $client);

function listCronJobs($client)
{
    try {
        $response = $client->cron->list();
    } catch (Servebolt\Sdk\Exceptions\ServeboltHttpClientException $exception) {
        $response = $exception->getResponseObject();
        echo '<pre>';
        print_r($response->getErrors());
        return;
    } catch (Exception $exception) {
        var_dump($exception->getCode());
        var_dump($exception->getMessage());
        return;
    }
    if ($response->wasSuccessful()) {
        echo '<pre>';
        foreach ($response->getResultItems() as $cronJob) {
            print_r($cronJob);
        }
    }
}
//listCronJobs($client);

function getCronJob($client, $id)
{
    try {
        $response = $client->cron->get($id);
        if ($response->wasSuccessful()) {
            echo '<pre>';
            print_r($response->getFirstResultItem());
        }
    } catch (Exception $e) {

    }
}
//getCronJob($client, $cronJobId);

function deleteCronJob($client, $id)
{
    try {
        $response = $client->cron->delete($id);
        var_dump($response->wasSuccessful());
    } catch (\Servebolt\Sdk\Exceptions\ServeboltHttpClientException $e) {
        echo '<pre>';
        print_r($e->getDecodeMessage());
        die;
    }
}
//deleteCronJob($client, $cronJobId);

function updateCronJob($client, $data, $id)
{
    try {
        $response = $client->cron->update($id, $data);
        var_dump($response->wasSuccessful());
    } catch (\Servebolt\Sdk\Exceptions\ServeboltHttpClientException $e) {
        echo '<pre>';
        print_r($e->getDecodeMessage());
        die;
    }
}
//updateCronJob($client, $cronJobUpdateData, $cronJobId);

function purgeCachePassingEnvIdThroughPurgeMethod($client)
{
    try {
        $environmentId = $_ENV['ENV_ID'];
        $response = $client->environment->purgeCache(
            $environmentId,
            [domain() . '/path/to/something'],
        );
    } catch (Servebolt\Sdk\Exceptions\ServeboltHttpClientException $exception) {
        $response = $exception->getResponseObject();
        echo '<pre>';
        print_r($response->getErrors());
        return;
    } catch (Exception $exception) {
        var_dump($exception->getCode());
        var_dump($exception->getMessage());
        return;
    }
    var_dump($response->wasSuccessful());
    if ($response->hasErrors()) {
        echo '<pre>';
        print_r($response->getErrors());
    }
}
//purgeCachePassingEnvIdThroughPurgeMethod($client);

function purgeCache($client)
{
    try {
        $environmentId = $_ENV['ENV_ID'];
        $response = $client->environment($environmentId)->purgeCache(
            [domain() . '/path/to/something'],
        );
    } catch (Servebolt\Sdk\Exceptions\ServeboltHttpClientException $exception) {
        $response = $exception->getResponseObject();
        echo '<pre>';
        print_r($response->getErrors());
        return;
    } catch (Exception $exception) {
        var_dump($exception->getCode());
        var_dump($exception->getMessage());
        return;
    }
    var_dump($response->wasSuccessful());
    if ($response->hasErrors()) {
        echo '<pre>';
        print_r($response->getErrors());
    }
}
//purgeCache($client);

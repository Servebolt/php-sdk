<?php

// phpcs:ignoreFile
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new Servebolt\Sdk\Client([
    'apiToken'   => $_ENV['API_TOKEN'],
    'baseUri'    => $_ENV['BASE_URI'], // Default: https://api.servebolt.io/v1/
    'authDriver' => $_ENV['AUTH_DRIVER'], // Default: apiToken
    'throwExceptionsOnClientError' => false, // Default: true
]);

$environmentId = $_ENV['ENV_ID'];
$cronJobData = [
    'environmentId' => 2368,
    'schedule' => '* * * * *',
    'command' => 'ls ./',
    'notifications' => 'none',
];

function createCronJobUsingFactory($cronJobData, $client)
{
    $model = Servebolt\Sdk\Models\CronJob::factory($cronJobData);
    $response = $client->cron->create($model);
    var_dump($response->wasSuccessful());
}
//createCronJobUsingFactory($cronJobData, $client);

function createCronJobUsingArrayOnly($cronJobData, $client) {
    try {
        $response = $client->cron->create();
        var_dump($response->wasSuccessful());
    } catch (\Servebolt\Sdk\Exceptions\ServeboltHttpClientException $e) {
        echo '<pre>';
        print_r($e->getDecodeMessage());
        die;
    }
}
//createCronJobUsingArrayOnly($cronJobData, client);

function printCronJobs($client)
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
        foreach ($response->getCronJobs() as $cronJob) {
            print_r($cronJob->schedule . ' ' . $cronJob->command);
        }
    }
}
//printCronJobs($client);

function purgeCache($client)
{
    try {
        $environmentId = $_ENV['ENV_ID'];
        $response = $client->environment->purgeCache(
            $environmentId,
            ['https://example.com/path/to/something'],
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

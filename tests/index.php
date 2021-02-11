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
    'authDriver' => $_ENV['AUTH_DRIVER'] // Default: apiToken
]);

function printCronJobs($client)
{
    try {
        $response = $client->cron->list();
        foreach ($response->getCronJobs() as $cronJob) {
            print_r($cronJob->schedule . ' ' . $cronJob->command);
        }
    } catch (Exception $exception) {
        var_dump($exception->getCode());
        var_dump($exception->getMessage());
    }
}
//printCronJobs($client);

function purgeCache($client)
{
    try {
        $environmentId = $_ENV['ENV_ID'];
        if ($client->environment->setEnvironment($environmentId)->cache->purge([
            'https://wtf.com/some/url/to/a/file.html',
        ])) {
            echo 'We purged cache!';
        }
    } catch (Servebolt\Sdk\Exceptions\ServeboltHttpClientException $exception) {
        $responseObject = $exception->getResponseObject();
    }
}
purgeCache($client);

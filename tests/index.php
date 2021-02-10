<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new Servebolt\Sdk\Client([
    'apiToken'   => $_ENV['API_TOKEN'],
    'baseUri'    => $_ENV['BASE_URI'],
    'authDriver' => $_ENV['AUTH_DRIVER']
]);

try {
    $response = $client->cron->list();
    foreach($response->getCronJobs() as $cronJob) {
        print_r($cronJob->schedule . ' ' . $cronJob->command);
    }
} catch (Exception $exception) {
    var_dump($exception->getCode());
    var_dump($exception->getMessage());
}

/*
$environmentId = $_ENV['ENV_ID'];
if ($client->environment->setEnvironment($environmentId)->cache->purge([
    'https://example.com/',
    'example.com/a/b/c',
    'example.com/a/b/c/',
], [
    'ssh://example.com',
    'http://example.com',
    'https://example.com',
    'example.com/some-path',
])) {
    echo 'We purged cache!';
}
*/

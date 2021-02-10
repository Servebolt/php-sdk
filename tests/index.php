<?php

require __DIR__ . '/../vendor/autoload.php';

use Servebolt\Sdk\Client;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$apiToken = $_ENV['API_TOKEN'];
$baseUri = $_ENV['BASE_URI'];
$environmentId = $_ENV['ENV_ID'];
$authDriver = isset($_ENV['AUTH_DRIVER']) ? $_ENV['AUTH_DRIVER'] : 'apiToken';

$client = new Client(compact('apiToken', 'baseUri', 'authDriver'));
echo '<pre>';

print_r($client->cron->list());
die;

/*
print_r($client->environment->setEnvironment($environmentId)->cache->purge([
    'https://example.com/',
    'example.com/a/b/c',
    'example.com/a/b/c/',
], [
    'ssh://example.com',
    'http://example.com',
    'https://example.com',
    'example.com/some-path',
]));
*/

<?php

require __DIR__ . '/../vendor/autoload.php';

use Servebolt\SDK\Client;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$apiKey = $_ENV['APIKEY'];
$baseUri = $_ENV['BASE_URI'];
$environmentId = $_ENV['ENV_ID'];
$authDriver = $_ENV['AUTH_DRIVER'] ?: 'apiKey';

$client = new Client(compact('apiKey', 'baseUri', 'authDriver'));
$client->environment->setEnvironment($environmentId)->cache->purge();

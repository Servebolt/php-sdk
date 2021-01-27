<?php

require __DIR__ . '/../vendor/autoload.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Servebolt\SDK\Client;

$apiKey = '';
$environmentId = '';

$client = new Client($apiKey);

$client->environment->set($environmentId)->cache->purge();

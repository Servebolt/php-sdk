<?php

require __DIR__ . '/../vendor/autoload.php';

use Servebolt\SDK\Client;

$apiKey = '01dd91406983eeccb07bcd128290849bd7b8ae191807039b2e48a58786746c32';
$environmentId = '13559';

$client = new Client();

$client->environment()->set($environmentId)->purgeCache();

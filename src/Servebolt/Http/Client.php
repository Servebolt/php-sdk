<?php

namespace Servebolt\SDK\Http;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Class Client
 * @package Servebolt\SDK\Http
 */
class Client {

    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * @var string
     */
    private $baseUri = 'https://api.servebolt.io/v1/';

    /**
     * Client constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->client = new GuzzleClient([
            'base_uri' => $this->baseUri,
            'headers' => [
                'Authorization' => 'Bearer ' . $config->get('apiKey'),
            ],
        ]);
    }

    public function get()
    {

    }

    public function post()
    {

    }

}

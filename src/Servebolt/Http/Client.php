<?php

namespace Servebolt\SDK\Http;

use Servebolt\SDK\Facades\Http;

/**
 * Class Client
 * @package Servebolt\SDK\Http
 */
class Client
{

    /**
     * @var string
     */
    private $baseUri = 'https://api.servebolt.io/v1/';

    /**
     * @var string[]
     */
    private array $headers;

    /**
     * Client constructor.
     * @param $config
     */
    public function __construct(array $config)
    {
        $this->headers = [
            'Authorization' => 'Bearer ' . $config['apiKey'],
        ];
    }

    public function get()
    {
        return Http::get($this->baseUri, $this->headers);
    }

    public function post()
    {
        return Http::post($this->baseUri, $this->headers);
    }
}

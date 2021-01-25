<?php

namespace Servebolt\SDK\Traits;

/**
 * Class HttpClient
 * @package Servebolt\SDK\Traits
 */
trait HttpClient {

    /**
     * HTTP client instance.
     *
     * @var
     */
    private $httpClient;

    /**
     * HttpClient constructor.
     * @param $httpClient
     */
    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

}

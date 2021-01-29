<?php

namespace Servebolt\SDK\Traits;

/**
 * Class ApiEndpoint
 * @package Servebolt\SDK\Traits
 */
trait ApiEndpoint {

    /**
     * @var
     */
    private $httpClient;

    /**
     * @var
     */
    private $config;

    /**
     * ApiEndpoint constructor.
     * @param $httpClient
     * @param $config
     */
    public function __construct($httpClient, $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
        if ( method_exists($this, 'loadHierarchicalEndpoints') ) {
            $this->loadHierarchicalEndpoints();
        }
    }

}

<?php

namespace Servebolt\SDK\Traits;

use Servebolt\SDK\ConfigHelper;
use Servebolt\SDK\Http\Client;

/**
 * Class ApiEndpoint
 * @package Servebolt\SDK\Traits
 */
trait ApiEndpoint
{

    private Client $httpClient;

    private ConfigHelper $config;

    /**
     * ApiEndpoint constructor.
     * @param Client $httpClient
     * @param ConfigHelper $config
     */
    public function __construct(Client $httpClient, ConfigHelper $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
        if (method_exists($this, 'loadHierarchicalEndpoints')) {
            $this->loadHierarchicalEndpoints();
        }
    }
}

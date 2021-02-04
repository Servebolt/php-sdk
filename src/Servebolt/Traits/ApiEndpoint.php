<?php

namespace Servebolt\SDK\Traits;

use Servebolt\SDK\ConfigHelper;
use Servebolt\SDK\Http\Client as HttpClient;

/**
 * Class ApiEndpoint
 * @package Servebolt\SDK\Traits
 */
trait ApiEndpoint
{
    /**
     * The configuration helper class.
     *
     * @var ConfigHelper
     */
    private ConfigHelper $config;

    /**
     * Guzzle HTTP client facade.
     *
     * @var HttpClient
     */
    public HttpClient $httpClient;

    /**
     * ApiEndpoint constructor.
     * @param HttpClient $httpClient
     * @param ConfigHelper $config
     */
    public function __construct(HttpClient $httpClient, ConfigHelper $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
        if (method_exists($this, 'loadHierarchicalEndpoints')) {
            $this->loadHierarchicalEndpoints();
        }
    }
}

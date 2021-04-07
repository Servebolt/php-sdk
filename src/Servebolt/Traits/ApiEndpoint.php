<?php

namespace Servebolt\Sdk\Traits;

use Servebolt\Sdk\ConfigHelper;
use Servebolt\Sdk\Http\Client as HttpClient;

/**
 * Class ApiEndpoint
 * @package Servebolt\Sdk\Traits
 */
trait ApiEndpoint
{
    /**
     * The configuration helper class.
     *
     * @var ConfigHelper
     */
    protected $config;

    /**
     * Guzzle HTTP client facade.
     *
     * @var HttpClient
     */
    public $httpClient;

    /**
     * ApiEndpoint constructor.
     * @param HttpClient $httpClient
     * @param ConfigHelper $config
     * @param array $arguments
     */
    public function __construct(HttpClient $httpClient, ConfigHelper $config, $arguments = [])
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
        if (method_exists($this, 'loadHierarchicalEndpoints')) {
            $this->loadHierarchicalEndpoints();
        }
        if (method_exists($this, 'loadArguments')) {
            $this->loadArguments($arguments);
        }
    }
}

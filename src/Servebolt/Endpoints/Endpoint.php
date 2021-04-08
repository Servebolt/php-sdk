<?php

namespace Servebolt\Sdk\Endpoints;

use Servebolt\Sdk\Response;
use Servebolt\Sdk\ConfigHelper;
use Servebolt\Sdk\Http\Client as HttpClient;
use ServeboltOptimizer_Vendor\GuzzleHttp\Psr7\Response as Psr7Response;

abstract class Endpoint
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

    /**
     * Conditional format on HTTP response.
     *
     * @param $httpResponse
     * @return object|Response|Psr7Response
     */
    protected function response($httpResponse)
    {
        switch ($this->config->get('responseObjectType')) {
            case 'psr7':
                return $httpResponse->getResponseObject();
            case 'decodedBody':
                return $httpResponse->getDecodedBody();
            case 'customResponse':
            default:
                return new Response(
                    $httpResponse->getDecodedBody(),
                    $httpResponse->getResponseObject()->getStatusCode(),
                    $this->getModelBinding()
                );
        }
    }

    /**
     * @return null|string
     */
    private function getModelBinding()
    {
        $class = get_class($this);
        if (property_exists($class, 'modelBinding') && $class::$modelBinding) {
            return $class::$modelBinding;
        }
    }
}

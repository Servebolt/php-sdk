<?php

namespace Servebolt\Sdk;

use Servebolt\Sdk\Auth\ApiToken;
use Servebolt\Sdk\Http\Client as HttpClient;
use Servebolt\Sdk\Exceptions\ServeboltInvalidOrMissingAuthDriverException;
use Servebolt\Sdk\Traits\RootApiEndpointsLoader;

/**
 * Class Client
 * @package Servebolt\Sdk
 */
class Client
{

    use RootApiEndpointsLoader;

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
     * Client constructor.
     * @param array $config
     * @throws ServeboltInvalidOrMissingAuthDriverException
     */
    public function __construct(array $config)
    {
        $this->initializeConfigHelper($config);
        $this->initializeHTTPClient();
        $this->readRootApiEndpoints();
    }

    /**
     * Initialize HTTP client.
     *
     * @throws ServeboltInvalidOrMissingAuthDriverException
     */
    private function initializeHTTPClient() : void
    {
        $this->httpClient = new HttpClient($this->getAuthenticationDriver(), $this->config);
    }

    /**
     * Determine which auth driver to be used with the HTTP client.
     *
     * @return ApiToken
     * @throws ServeboltInvalidOrMissingAuthDriverException
     */
    private function getAuthenticationDriver() : object
    {
        switch (strtolower($this->config->get('authDriver'))) {
            case 'apitoken':
            default:
                if ($apiToken = $this->config->get('apiToken')) {
                    return new ApiToken($apiToken);
                }
        }
        throw new ServeboltInvalidOrMissingAuthDriverException(
            'Invalid or missing auth driver for client.'
        ); // Invalid auth driver
    }

    /**
     * Initialize configuration helper.
     * @param string|array|null $config
     * @return bool
     */
    private function initializeConfigHelper($config = null) : bool
    {
        $this->config = new ConfigHelper;
        if ($config) {
            return $this->setConfig($config);
        }
        return true;
    }

    /**
     * Set configuration.
     *
     * @param string|array $config
     * @return bool
     */
    private function setConfig(array $config) : bool
    {
        if (!empty($config)) {
            $this->config->setWithArray($config);
            return true;
        }
        return false; // No configuration was passed
    }
}

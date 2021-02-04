<?php

namespace Servebolt\SDK;

use Servebolt\SDK\Auth\ApiToken;
use Servebolt\SDK\Http\Client as HttpClient;
use Servebolt\SDK\Exceptions\ServeboltInvalidAuthDriver;

/**
 * Class Client
 * @package Servebolt\SDK
 */
class Client
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
     * Client constructor.
     * @param array $config
     * @throws ServeboltInvalidAuthDriver
     */
    public function __construct(array $config)
    {
        $this->initializeConfigHelper($config);
        $this->initializeHTTPClient();
        $this->initializeApiEndpoints();
    }

    /**
     * Initialize API endpoints.
     */
    public function initializeApiEndpoints() : void
    {
        $namespaceFolders = glob(__DIR__ . '/Endpoints/*');
        foreach ($namespaceFolders as $namespaceFolderPath) {
            $namespace = basename($namespaceFolderPath, '.php');
            $lowercaseNamespace = mb_strtolower($namespace);
            if (is_dir($namespaceFolderPath)) {
                $classNameWithNamespace = '\\Servebolt\\SDK\\Endpoints\\' . $namespace . '\\' . $namespace;
            } else {
                $classNameWithNamespace = '\\Servebolt\\SDK\\Endpoints\\' . $namespace;
            }
            $this->{ $lowercaseNamespace } = new $classNameWithNamespace($this->httpClient, $this->config);
        }
    }

    /**
     * Initialize HTTP client.
     *
     * @throws ServeboltInvalidAuthDriver
     */
    private function initializeHTTPClient() : void
    {
        $this->httpClient = new HttpClient($this->getAuthenticationDriver(), $this->config);
    }

    /**
     * Determine which auth driver to be used with the HTTP client.
     *
     * @return ApiToken
     * @throws ServeboltInvalidAuthDriver
     */
    private function getAuthenticationDriver() : object
    {
        switch (strtolower($this->config->get('authDriver'))) {
            case 'apitoken':
            default:
                return new ApiToken($this->config->get('apiToken'));
        }
        throw new ServeboltInvalidAuthDriver; // Invalid auth driver
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

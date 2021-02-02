<?php

namespace Servebolt\SDK;

use Servebolt\SDK\Auth\ApiKey;
use Servebolt\SDK\Http\Client as HttpClient;
use Servebolt\SDK\Exceptions\ServeboltInvalidAuthDriver;

/**
 * Class Client
 * @package Servebolt\SDK
 */
class Client
{

    /**
     * The config class containing configuration values.
     *
     * @var ConfigHelper
     */
    private ConfigHelper $config;

    /**
     * Guzzle HTTP client facade.
     */
    public HttpClient $httpClient;

    /**
     * Client constructor.
     * @param string|array $config
     * @throws ServeboltInvalidAuthDriver
     */
    public function __construct($config)
    {
        $this->initializeConfigHelper($config);
        $this->initializeHTTPClient();
        $this->initializeApiEndpoints();
    }

    /**
     * Initialize API endpoints.
     */
    public function initializeApiEndpoints()
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
    private function initializeHTTPClient()
    {
        $this->httpClient = new HttpClient($this->getAuthenticationDriver(), $this->config);
    }

    /**
     * Determine which auth driver to be used with the HTTP client.
     *
     * @return ApiKey
     * @throws ServeboltInvalidAuthDriver
     */
    private function getAuthenticationDriver() : object
    {
        switch ($this->config->get('authDriver')) {
            case 'APIKEY':
            case 'apiKey':
            default:
                return new ApiKey($this->config->get('apiKey'));
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
    private function setConfig($config) : bool
    {
        if (is_string($config)) {
            $this->config->set('apiKey', $config);
            return true;
        } elseif (is_array($config)) {
            $this->config->setWithArray($config);
            return true;
        }
        return false; // No valid configuration was passed
    }
}

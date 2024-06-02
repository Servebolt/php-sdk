<?php

namespace Servebolt\Sdk;

use Servebolt\Sdk\Auth\ApiToken;
use Servebolt\Sdk\Http\Client as HttpClient;
use Servebolt\Sdk\Exceptions\ServeboltInvalidOrMissingAuthDriverException;
use Servebolt\Sdk\Traits\ApiEndpointsLoader;

/**
 * Class Client
 * @package Servebolt\Sdk
 */
class Client
{

    use ApiEndpointsLoader;

    /**
     * The configuration helper class.
     *
     * @var ConfigHelper
     */
    private $config;

    /**
     * Guzzle HTTP client facade.
     *
     * @var HttpClient
     */
    public $httpClient;

    /**
     * Static accessor for client instance.
     *
     * @var Client
     */
    private static $instance;

    /**
     * Client constructor.
     * @param array $config An array of configuration variables
     * @param bool $storeStatically Whether to make this instance statically accessible
     * @throws ServeboltInvalidOrMissingAuthDriverException
     */
    public function __construct(array $config, bool $storeStatically = true)
    {
        if (!defined('SB_SDK_VERSION')) {
            define('SB_SDK_VERSION', '1.2.4');
        }
        $this->initializeConfigHelper($config);
        $this->initializeHTTPClient();
        $this->loadEndpoints();
        if ($storeStatically) {
            $this->allowStaticClientAccess();
        }
    }

    /**
     * Static Client accessor.
     *
     * @return Client|null
     */
    public static function getInstance(): ?object
    {
        if (self::$instance) {
            return self::$instance;
        }
        return null;
    }

    /**
     * Store the
     */
    private function allowStaticClientAccess(): void
    {
        if (!self::$instance) {
            self::$instance = $this;
        }
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
        $value = '';
        if ($this->config->get('authDriver') != null) {
            $value = strtolower($this->config->get('authDriver'));
        }
        switch ($value) {
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

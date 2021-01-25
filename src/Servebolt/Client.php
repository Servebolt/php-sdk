<?php

namespace Servebolt\SDK;

/**
 * Class Client
 * @package Servebolt\SDK
 */
class Client {

    /**
     * The array containing configuration values.
     *
     * @var array
     */
    private $configArray = [];

    /**
     * Guzzle HTTP client facade.
     *
     * @var
     */
    public $httpClient;

    /**
     * Client constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->setConfig($config);
        $this->initializeHTTPClient();
        $this->initializeApiNamespaces();
    }

    public function initializeApiNamespaces()
    {
        // TODO: Instantiate API namespaces
    }

    private function initializeHTTPClient()
    {
        // TODO: Create Guzzle facade instance
    }

    /**
     * Set configuration.
     *
     * @param $config
     * @return bool
     */
    private function setConfig($config) : bool
    {
        if(is_string($config)) {
            $this->configArray['apiKey'] = $config; // Only API key was passed as configration
            return true;
        }elseif(is_array($config)){
            $this->configArray = $this->configArray + $config; // An array of configuration values was passed
            return true;
        }
        return false; // No valid configuration was passed
    }

}

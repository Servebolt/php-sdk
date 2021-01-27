<?php

namespace Servebolt\SDK;

use Servebolt\SDK\Http\Client as HttpClient;

/**
 * Class Client
 * @package Servebolt\SDK
 */
class Client {

    /**
     * The config class containing configuration values.
     *
     * @var array
     */
    private $config;

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

    /**
     * Initialie API namespaces.
     */
    public function initializeApiNamespaces()
    {
        $namespaceFolders = array_filter(glob(__DIR__ . '/Namespaces/*'), function($filePath) { return is_dir($filePath); });
        foreach($namespaceFolders as $namespaceFolderPath) {
            $namespace = basename($namespaceFolderPath);
            $lowercaseNamespace = mb_strtolower($namespace);
            $classNameWithNamespace = '\\Servebolt\\SDK\\Namespaces\\' . $namespace . '\\' . $namespace;
            $this->{ $lowercaseNamespace } = new $classNameWithNamespace($this->httpClient);
        }
    }

    /**
     * Initialize HTTP client.
     */
    private function initializeHTTPClient()
    {
        $this->httpClient = new HttpClient($this->config);
        // TODO: Create Guzzle facade instance
    }

    /**
     * Initialize configuration helper.
     */
    private function initConfig()
    {
        if(is_null($this->config)) {
            $this->config = new ConfigHelper;
        }
    }

    /**
     * Set configuration.
     *
     * @param $config
     * @return bool
     */
    private function setConfig($config) : bool
    {
        $this->initConfig();
        if(is_string($config)) {
            $this->config->set('apiKey', $config);
            return true;
        }elseif(is_array($config)){
            $this->config->setWithArray($config);
            return true;
        }
        return false; // No valid configuration was passed
    }

}

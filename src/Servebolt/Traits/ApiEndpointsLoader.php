<?php

namespace Servebolt\Sdk\Traits;

trait ApiEndpointsLoader
{

    /**
     * @var ConfigHelper
     * @since php8.2
     */
    private $environment; 

    private $apiEndpoints = [];

    /**
     * @param $name
     * @return object|void
     */
    public function __get($name)
    {
        if ($this->apiEndpointExists($name)) {
            return $this->initializeApiEndpoint($name);
        }
        trigger_error(sprintf('Undefined property: $%s', $name), E_USER_NOTICE);
    }

    /**
     * @param $name
     * @param $arguments
     * @return object|void
     */
    public function __call($name, $arguments)
    {
        if (!$this->apiEndpointExists($name)) {
            trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
            return;
        }

        $hasArguments = !empty($arguments);
        $shouldReinitialize = $hasArguments;
        $alreadyInitialized = property_exists($this, $name);

        if ($alreadyInitialized && $shouldReinitialize) {
            $this->initializeApiEndpoint($name);
        } else {
            $this->initializeApiEndpoint($name, $hasArguments ? $arguments : null);
        }
        return $this->{$name};
    }

    private function loadEndpoints(): void
    {
        $rootPath = __DIR__ . '/../Endpoints/';
        $namespaceFolders = glob($rootPath . '*');
        $filesToIgnore = ['Endpoint.php'];
        foreach ($namespaceFolders as $namespaceFolderPath) {
            $fileBaseName = str_replace($rootPath, '', $namespaceFolderPath);
            if (in_array($fileBaseName, $filesToIgnore)) {
                continue;
            }
            $namespace = basename($namespaceFolderPath, '.php');
            $lowercaseNamespace = mb_strtolower($namespace);
            $baseNamespace = "\Servebolt\Sdk\Endpoints";
            if (is_dir($namespaceFolderPath)) {
                $class = sprintf("%s\%s\%s", $baseNamespace, $namespace, $namespace);
            } else {
                $class = sprintf("%s\%s", $baseNamespace, $namespace);
            }
            $this->apiEndpoints[$lowercaseNamespace] = $class;
        }
    }

    /**
     * @param $name
     * @return false|string
     */
    private function resolveApiEndpointClass($name)
    {
        if (array_key_exists($name, $this->apiEndpoints)) {
            return $this->apiEndpoints[$name];
        }
        return false;
    }

    private function apiEndpointExists($name) : bool
    {
        return $this->resolveApiEndpointClass($name) !== false;
    }

    private function initializeApiEndpoint($name, $arguments = null) : object
    {
        $classNameWithNamespace = $this->resolveApiEndpointClass($name);
        $this->{$name} = new $classNameWithNamespace($this->httpClient, $this->config, $arguments);
        return $this->{$name};
    }
}

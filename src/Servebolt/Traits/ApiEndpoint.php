<?php

namespace Servebolt\SDK\Traits;

/**
 * Class ApiEndpoint
 * @package Servebolt\SDK\Traits
 */
trait ApiEndpoint {

    /**
     * @var
     */
    private $httpClient;

    /**
     * @var
     */
    private $config;

    /**
     * ApiEndpoint constructor.
     * @param $httpClient
     * @param $config
     */
    public function __construct($httpClient, $config)
    {
        $this->httpClient = $httpClient;
        $this->config = $config;
        $this->loadHierarchicalEndpoints();
    }

    /**
     * Check whether the current endpoint has a hierarchical structure.
     *
     * @param null $reflectionClass
     * @return bool
     */
    private function hasHierarchy($reflectionClass = null) : bool
    {
        if ( ! $reflectionClass ) {
            $reflectionClass = new \ReflectionClass(__CLASS__);
        }
        $traits = $reflectionClass->getTraits();
        if (
            basename(dirname($reflectionClass->getFileName())) === $reflectionClass->getShortName()
            && in_array('Servebolt\SDK\Traits\HasHierarchy', array_keys($traits))
        ) {
            return true;
        }
        return  false;
    }

    /**
     * Check if the current endpoint has a hierarchy, and if so initialize it.
     */
    private function loadHierarchicalEndpoints() : void
    {
        $reflectionClass = (new \ReflectionClass(__CLASS__));
        if ( ! $this->hasHierarchy($reflectionClass) ) return; // This endpoint does not have a hierarchical structure
        $files = glob(dirname($reflectionClass->getFileName()) . '/*');
        foreach($files as $file) {
            $className = basename($file, '.php');
            if ( $className === $reflectionClass->getShortName() ) {
                continue;
            }
            $lowercaseClassname = mb_strtolower($className);
            if ( is_dir($file) ) {
                $classNameWithNamespace = $reflectionClass->getNamespaceName() . '\\' . $className . '\\' . $className;
            } else {
                $classNameWithNamespace = $reflectionClass->getNamespaceName() . '\\' . $className;
            }
            $this->{ $lowercaseClassname } = new $classNameWithNamespace($this->httpClient, $this->config);
        }
    }

}

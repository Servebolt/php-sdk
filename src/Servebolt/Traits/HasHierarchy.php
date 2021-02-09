<?php

namespace Servebolt\Sdk\Traits;

/**
 * Class HasHierarchy
 * @package Servebolt\Sdk\Traits
 */
trait HasHierarchy
{

    /**
     * Check if the current endpoint has a hierarchy, and if so initialize it.
     */
    private function loadHierarchicalEndpoints() : void
    {
        $reflectionClass = (new \ReflectionClass(__CLASS__));
        $files = glob(dirname($reflectionClass->getFileName()) . '/*');
        foreach ($files as $file) {
            $className = basename($file, '.php');
            if ($className === $reflectionClass->getShortName()) {
                continue;
            }
            $lowercaseClassname = mb_strtolower($className);
            if (is_dir($file)) {
                $classNameWithNamespace = $reflectionClass->getNamespaceName() . '\\' . $className . '\\' . $className;
            } else {
                $classNameWithNamespace = $reflectionClass->getNamespaceName() . '\\' . $className;
            }
            $this->{ $lowercaseClassname } = new $classNameWithNamespace($this->httpClient, $this->config);
        }
    }
}

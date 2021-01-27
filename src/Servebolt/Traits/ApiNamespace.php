<?php

namespace Servebolt\SDK\Traits;

/**
 * Class ApiNamespace
 * @package Servebolt\SDK\Traits
 */
trait ApiNamespace {

    /**
     * @var
     */
    private $httpClient;

    /**
     * ApiNamespace constructor.
     * @param $httpClient
     */
    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
        $this->loadNamespaceClasses();
    }

    private function loadNamespaceClasses()
    {

    }

}

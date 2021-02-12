<?php

namespace Servebolt\Sdk\Endpoints\Environment;

use Servebolt\Sdk\Endpoints\Endpoint;
use Servebolt\Sdk\Traits\ApiEndpoint;
use Servebolt\Sdk\Traits\HasHierarchy;

/**
 * Class Environment
 * @package Servebolt\Sdk\Endpoints
 */
class Environment extends Endpoint
{

    use ApiEndpoint, HasHierarchy;

    protected string $environmentId;

    /**
     * @param $environmentId
     * @return $this
     */
    public function setEnvironment(string $environmentId) : Environment
    {
        $this->config->set('environmentId', $environmentId);
        return $this;
    }
}

<?php

namespace Servebolt\SDK\Endpoints\Environment;

use Servebolt\SDK\Traits\ApiEndpoint;
use Servebolt\SDK\Traits\HasHierarchy;

/**
 * Class Environment
 * @package Servebolt\SDK\Endpoints
 */
class Environment
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

<?php

namespace Servebolt\SDK\Endpoints\Environment;

use Servebolt\SDK\Traits\ApiEndpoint;
use Servebolt\SDK\Traits\HasHierarchy;

/**
 * Class Environment
 * @package Servebolt\SDK\Endpoints
 */
class Environment {

    use ApiEndpoint, HasHierarchy;

    protected $environmentId;

    /**
     * @param $environmentId
     * @return $this
     */
    public function setEnvironment($environmentId)
    {
        $this->environmentId = $environmentId;
        return $this;
    }

}

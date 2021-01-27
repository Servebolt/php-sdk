<?php

namespace Servebolt\SDK\Namespaces\Environment;

use Servebolt\SDK\Traits\ApiNamespace;

/**
 * Class Environment
 * @package Servebolt\SDK\Namespaces
 */
class Environment {

    use ApiNamespace;

    public function set($environmentId)
    {
        return $this;
    }

}

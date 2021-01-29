<?php

namespace Servebolt\SDK\Endpoints\Environment;

use Servebolt\SDK\Traits\ApiEndpoint;

/**
 * Class Cache
 * @package Servebolt\SDK\Endpoints
 */
class Cache {

    use ApiEndpoint;

    public function purge()
    {
        echo 'Purge!';
    }

}

<?php

namespace Servebolt\SDK\Endpoints\Environment;

use Servebolt\SDK\Traits\ApiEndpoint;

/**
 * Class Cache
 * @package Servebolt\SDK\Endpoints
 */
class Cache
{

    use ApiEndpoint;

    /**
     * @param array $files
     * @param array $prefixes
     */
    public function purge(array $files = [], array $prefixes = [])
    {
        return $this->httpClient->post('/environments/' . $this->config->get('environmentId') . '/purge_cache/');
    }
}

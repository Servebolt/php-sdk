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
     * Purge cache for given files or prefixes.
     *
     * @param string[] $files
     * @param string[] $prefixes
     * @return bool
     */
    public function purge(array $files = [], array $prefixes = []) : bool
    {
        // TODO: Make sure $files and $prefixes only contains an array with strings
        $response = $this->httpClient->post('/environments/' . $this->config->get('environmentId') . '/purge_cache/', [], compact('files', 'prefixes'));
        $body = json_decode($response->getBody());
        // TODO: Handle partial success
        if (isset($body->success) && $body->success) {
            return true;
        }
        return false;
    }
}

<?php

namespace Servebolt\SDK\Endpoints\Environment;

use Servebolt\SDK\Traits\ApiEndpoint;
use Servebolt\SDK\Helpers as Helpers;

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
        $files = array_filter(array_map(function ($file) {
            return Helpers\sanitizeUrl($file);
        }, $files));
        $prefixes = array_filter(array_map(function ($prefix) {
            return Helpers\sanitizeDomain($prefix);
        }, $prefixes));
        $body = compact('files', 'prefixes');
        $requestUrl = '/environments/' . $this->config->get('environmentId') . '/purge_cache';
        // TODO: Make sure $files and $prefixes only contains an array with strings
        $response = $this->httpClient->post($requestUrl, $body);
        $body = json_decode($response->getBody());
        // TODO: Handle partial success
        if (isset($body->success) && $body->success) {
            return true;
        }
        return false;
    }
}

<?php

namespace Servebolt\SDK\Endpoints\Environment;

use Servebolt\SDK\Exceptions\ServeboltInvalidUrlException;
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
     * @param string $url
     * @throws ServeboltInvalidUrlException
     */
    public static function validateUrl(string $url): void
    {
        $parts = parse_url($url);
        if (!is_array($parts)) {
            throw new ServeboltInvalidUrlException($url . ' is not a valid URL');
        } elseif (!array_key_exists('scheme', $parts)) {
            $parts = parse_url('http://' . $url);
        }
        if (false !== filter_var($parts['host'], FILTER_VALIDATE_IP)) {
            throw new ServeboltInvalidUrlException($url . ' is not a valid URL');
        }
        if (false === filter_var($parts['host'], FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            throw new ServeboltInvalidUrlException($url . ' is not a valid URL');
        }
        if (array_key_exists('fragment', $parts) || array_key_exists('port', $parts)) {
            // @todo: provide more detail
            throw new ServeboltInvalidUrlException($url . ' is not a valid URL');
        }
    }

    /**
     * @param string[] $urls
     * @throws ServeboltInvalidUrlException
     */
    public static function validateUrls(array $urls): void
    {
        foreach ($urls as $url) {
            self::validateUrl($url);
        }
    }

    /**
     * @param string[] $urls
     * @return string[]
     */
    public static function sanitizeFiles(array $urls) : array
    {
        return array_map(function (string $url) {
            $parts = parse_url($url);
            if (!array_key_exists('scheme', $parts)) {
                $parts = parse_url('https://' . $url);
            }
            $scheme = $parts['scheme'] ?? '';
            if (!in_array($scheme, ['http', 'https'])) {
                var_dump($parts);
                $parts['scheme'] = 'https';
            }
            return http_build_url($parts);
        }, $urls);
    }

    /**
     * @param string[] $urls
     * @return string[]
     */
    public static function sanitizePrefixes(array $urls) : array
    {
        return $urls;
    }

    /**
     * Purge cache for given files or prefixes.
     *
     * @param string[] $files
     * @param string[] $prefixes
     * @return bool
     * @throws ServeboltInvalidUrlException
     */
    public function purge(array $files = [], array $prefixes = []) : bool
    {
        self::validateUrls($files);
        self::validateUrls($prefixes);

        $files = self::sanitizeFiles($files);
        $prefixes = self::sanitizePrefixes($prefixes);

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

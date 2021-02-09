<?php

namespace Servebolt\Sdk\Endpoints\Environment;

use Servebolt\Sdk\Exceptions\ServeboltInvalidUrlException;
use Servebolt\Sdk\Traits\ApiEndpoint;
use Servebolt\Sdk\Helpers as Helpers;

/**
 * Class Cache
 * @package Servebolt\Sdk\Endpoints
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
            throw new ServeboltInvalidUrlException(sprintf('"%s" is not a valid URL', $url));
        } elseif (!array_key_exists('scheme', $parts)) {
            $parts = parse_url('http://' . $url);
        }
        if (false !== filter_var($parts['host'], FILTER_VALIDATE_IP)) {
            throw new ServeboltInvalidUrlException(sprintf('"%s" is not a valid URL', $url));
        }
        if (false === filter_var($parts['host'], FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            throw new ServeboltInvalidUrlException(sprintf('"%s" is not a valid URL', $url));
        }
        if (array_key_exists('fragment', $parts) || array_key_exists('port', $parts)) {
            // @todo: provide more detail
            throw new ServeboltInvalidUrlException(sprintf('"%s" is not a valid URL', $url));
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
                $parts['scheme'] = 'https';
            }
            return http_build_url($parts);
        }, $urls);
    }

    /**
     * @param string[] $prefixes
     * @return string[]
     */
    public static function sanitizePrefixes(array $prefixes) : array
    {
        return array_map(function (string $prefix) {
            $prefix = preg_replace(
                '/^^([a-zA-Z].+):\/\//',
                '',
                trim($prefix)
            ); // Remove scheme
            return $prefix;
        }, $prefixes);
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
        $requestData = compact('files', 'prefixes');

        $requestUrl = '/environments/' . $this->config->get('environmentId') . '/purge_cache';
        $response = $this->httpClient->post($requestUrl, $requestData);
        $body = json_decode($response->getBody());
        // TODO: Handle partial success
        if (isset($body->success) && $body->success) {
            return true;
        }
        return false;
    }
}

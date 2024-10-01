<?php

namespace Servebolt\Sdk\Endpoints;

use Servebolt\Sdk\Exceptions\ServeboltInvalidUrlException;
use Servebolt\Sdk\Exceptions\ServeboltInvalidHostnameException;

/**
 * Class Environment
 * @package Servebolt\Optimizer\Dependencies\Servebolt\Sdk\Endpoints
 */
class Environment extends AbstractEndpoint
{
    /**
     * @var string
     */
    protected $endpoint = 'environments';

    /**
     * @var int|null
     */
    protected $environmentId;

    public function loadArguments($arguments) : void
    {
        $this->environmentId = (isset($arguments[0]) ? $arguments[0] : null);
    }

    public function get($id)
    {
        $httpResponse = $this->httpClient->get('/' . $this->endpoint . '/' . $id);
        return $this->response($httpResponse);
    }

    public function update($id, $data)
    {
        //$data = $this->appendCommonRequestData($data);
        $httpResponse = $this->httpClient->patchJson('/' . $this->endpoint . '/' . $id, compact('data'));
        return $this->response($httpResponse);
    }

    /**
     * Purge CDN cache for given hosts.
     *
     * @param integer|null $environmentId
     * @param array $hosts
     * @param array $tags
     * @param string|null $type
     * @return \Servebolt\Optimizer\Dependencies\GuzzleHttp\Psr7\Response|object|\Servebolt\Optimizer\Dependencies\Servebolt\Sdk\Response
     * @throws ServeboltInvalidUrlException
     */
    public function purgeCdnCache(
        ?int $environmentId = null,
        array $files = [],
        array $prefixes = [],
        array $tags = [],
        array $hosts = [],
        string $type = 'serveboltcdn')
    {

        $hosts = self::sanitizeHosts($hosts);
        $files = self::sanitizeFiles($files);
        $prefixes = self::sanitizePrefixes($prefixes);

        self::validateUrls($files);
        self::validateUrls($prefixes);
        self::validateHostnames($hosts);

        if (is_array($environmentId)) { // Offset method argument order
            $prefixes = $files;
            $files = $environmentId;
            $environmentId = null;
        }

        $requestData = array_filter(compact('files', 'prefixes', 'hosts', 'type', 'tags'));
        return $this->purgeCacheByArguments($environmentId, $requestData);
    }

    /**
     * Purge cache for given files or prefixes.
     *
     * @param integer|null|array $environmentId
     * @param string[] $files
     * @param string[] $prefixes
     * @param string[] $tags
     * @param string[] $hosts
     * @return Response|object
     * @throws ServeboltInvalidUrlException
     * @throws \Servebolt\Optimizer\Dependencies\Servebolt\Sdk\Exceptions\ServeboltInvalidJsonException
     */
    public function purgeCache(
        $environmentId = null,
        array $files = [],
        array $prefixes = [],
        array $tags = [],
        array $hosts = []
    ) {

        $hosts = self::sanitizeHosts($hosts);
        $files = self::sanitizeFiles($files);
        $prefixes = self::sanitizePrefixes($prefixes);

        self::validateUrls($files);
        self::validateUrls($prefixes);
        self::validateHostnames($hosts);

        if (is_array($environmentId)) { // Offset method argument order
            $prefixes = $files;
            $files = $environmentId;
            $environmentId = null;
        }

        $requestData = array_filter(compact('files', 'prefixes', 'tags', 'hosts'));
        return $this->purgeCacheByArguments($environmentId, $requestData);
    }

    /**
     * Send cache purge request.
     *
     * @param integer|null $environmentId
     * @param array $args
     * @return \Servebolt\Optimizer\Dependencies\GuzzleHttp\Psr7\Response|object|\Servebolt\Optimizer\Dependencies\Servebolt\Sdk\Response
     */
    public function purgeCacheByArguments(?int $environmentId = null, array $args = [])
    {
        $args = $this->filterArrayByKeys($args, ['files', 'prefixes', 'hosts', 'type', 'tags']);
        $environmentId = is_numeric($environmentId) ? $environmentId : $this->environmentId;
        $requestUrl = '/environments/' . $environmentId . '/purge_cache';
        $httpResponse = $this->httpClient->postJson($requestUrl, $args);
        return $this->response($httpResponse);
    }

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
     * @param string $hostname
     * @throws ServeboltInvalidUrlException
     */
    public static function validateHostname(string $hostname): void
    {
        if (!is_string($hostname) || !filter_var($hostname, FILTER_VALIDATE_DOMAIN)) {
            throw new ServeboltInvalidHostnameException(sprintf('"%s" is not a valid hostname', $hostname));
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
     * @param string[] $hostnames
     * @throws ServeboltInvalidUrlException
     */
    public static function validateHostnames(array $hostnames): void
    {
        foreach ($hostnames as $hostname) {
            self::validateHostname($hostname);
        }
    }


    /**
     * @param string[] $hosts
     * @return string[]
     */
    public static function sanitizeHosts(array $hosts) : array
    {
        $output = [];
        foreach ($hosts as $host) {
            // if path, protocol and GET vars are not present, its a host
            if (strpos($host, '/') === false && strpos($host, '?') === false ) {
                $output[] = $host;
            } elseif ( strpos($host, '://') !== false ) {
            // its got a protocol, so its a URL, we will extract the host
                $output[] = parse_url($host, PHP_URL_HOST);
            } elseif ( strpos($host, '/') === 0){
            // its a path only, we will remove it
                continue;
            } elseif ( strpos($host, '/') !== false ) {
            // its a path, we will remove it, and thus any ? GET vars
                $output[] = strtok($host, '/');
            } else {
            // there is no path, but there are GET vars, we will remove them
                $output[] = strtok($host, '?');
            }
        }
        return array_unique($output);
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
     * @param string[] $tags
     * @return string[]
     */
    public static function sanitizeTags(array $tags) : array
    {
        return array_map(function (string $tag) {
            $tag = preg_replace(
                '/([a-z0-9_]+)/',
                '',
                trim($tag)
            );// Remove invald characters.
            return $tag;
        }, $tags);
    }
}

<?php

namespace Servebolt\SDK\Helpers;

use \Servebolt\SDK\Exceptions\ServeboltUrlWasSanitizedException;
use \Servebolt\SDK\Exceptions\ServeboltDomainWithPathWasSanitizedException;

/**
 * @param string $url
 * @param bool $throwExceptions
 * @return string
 * @throws ServeboltUrlWasSanitizedException
 */
function sanitizeUrl(string $url, $throwExceptions = true) : string
{

    $newUrl = filter_var($url, FILTER_SANITIZE_URL); // Remove any invalid characters
    $newUrl = http_build_url(parse_url($newUrl)); // Break down and rebuild URL
    if ($throwExceptions && $url !== $newUrl) {
        throw new ServeboltUrlWasSanitizedException(sprintf('URL "%s" was sanitized to "%s".', $url, $newUrl));
    }
    return $newUrl;
}

/**
 * @param array $urls
 * @param bool $throwExceptions
 * @return array
 * @throws ServeboltUrlWasSanitizedException
 */
function sanitizeUrls(array $urls, $throwExceptions = true) : array
{
    return array_filter(array_map(function ($url) use ($throwExceptions) {
        return sanitizeUrl($url, $throwExceptions);
    }, $urls));
}

/**
 * @param string $domainWithPath
 * @param bool $throwExceptions
 * @return string
 * @throws ServeboltDomainWithPathWasSanitizedException
 */
function sanitizeDomainWithPath(string $domainWithPath, $throwExceptions = true) : string
{
    $domainWithPath = filter_var($domainWithPath, FILTER_SANITIZE_URL); // Remove any invalid characters
    $parsedUrl = parse_url('https://' . $domainWithPath);
    $host = array_key_exists('host', $parsedUrl) ? $parsedUrl['host'] : null;
    $path = array_key_exists('path', $parsedUrl) ? $parsedUrl['path'] : null;
    $newDomainWithPath = $host . $path;
    if ($throwExceptions && $domainWithPath !== $newDomainWithPath) {
        throw new ServeboltDomainWithPathWasSanitizedException(
            sprintf('Domain "%s" was sanitized to "%s".', $domainWithPath, $newDomainWithPath)
        );
    }
    return $newDomainWithPath;
}

/**
 * @param array $domainsWithPath
 * @param bool $throwExceptions
 * @return array
 * @throws ServeboltUrlWasSanitizedException
 */
function sanitizeDomainsWithPath(array $domainsWithPath, $throwExceptions = true) : array
{
    return array_filter(array_map(function ($domainWithPath) use ($throwExceptions) {
        return sanitizeDomainWithPath($domainWithPath, $throwExceptions);
    }, $domainsWithPath));
}

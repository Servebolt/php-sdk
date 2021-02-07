<?php

namespace Servebolt\SDK\Helpers;

use \Servebolt\SDK\Exceptions\ServeboltUrlWasSanitizedException;

/**
 * @param string $url
 * @param bool $throwExceptions
 * @return mixed|string|null
 * @throws ServeboltUrlWasSanitizedException
 */
function sanitizeUrl(string $url, $throwExceptions = true)
{

    $newUrl = filter_var($url, FILTER_SANITIZE_URL); // Remove any invalid characters
    $newUrl = http_build_url(parse_url($newUrl)); // Break down and rebuild URL
    if ($throwExceptions && $url !== $newUrl) {
        throw new ServeboltUrlWasSanitizedException(sprintf('URL "%s" was sanitized to "%s".', $url, $newUrl));
    }
    return $newUrl;
}

function sanitizeUrls(array $urls, $throwExceptions = true) : array
{
    return array_filter(array_map(function($url) use ($throwExceptions) {
        return sanitizeUrl($url, $throwExceptions);
    }, $urls));
}

function sanitizeDomainWithPath(string $domain, bool $allowPath = true, bool $allowQueryString = false) : string
{
    // TODO: Throw exception if failed
    return $domain;
}

function sanitizeDomainsWithPath(array $domains, bool $allowPath = true, bool $allowQueryString = false) : array
{
    $validDomains = [];
    foreach ($domains as $domain) {
        if (sanitizeDomainWithPath($domain, $allowPath, $allowQueryString)) {
            $validDomains[] = $domain;
        }
    }
    return $validDomains;
}

/**
 * @param $url
 * @return bool
 */
function urlIsValid($url) : bool
{
    return (
    (
        str_starts_with($url, 'http://')
        || str_starts_with($url, 'https://')
    )
        //&& filter_var($url, FILTER_VALIDATE_URL)
    );
}

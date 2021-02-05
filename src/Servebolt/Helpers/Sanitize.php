<?php

namespace Servebolt\SDK\Helpers;

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

/**
 * @param string $url
 * @param bool $throwExceptions
 * @return mixed|string|null
 * @throws \Servebolt\SDK\Exceptions\ServeboltInvalidUrlException
 * @throws \Servebolt\SDK\Exceptions\ServeboltUrlWasSanitizedException
 */
function sanitizeUrl(string $url, $throwExceptions = true)
{
    /*
    if (!urlIsValid($url)) {
        if ($throwExceptions) {
            throw new \Servebolt\SDK\Exceptions\ServeboltInvalidUrlException(sprintf('URL "%s" is not valid.', $url));
        }
        return null;
    }
    */
    $newUrl = filter_var($url, FILTER_SANITIZE_URL);
    if ($throwExceptions && $url !== $newUrl) {
        throw new \Servebolt\SDK\Exceptions\ServeboltUrlWasSanitizedException(sprintf('URL "%s" was sanitized to "%s".', $url, $newUrl));
    }
    return $newUrl;
}

function sanitizeUrls(array $urls) : array
{
    $validUrls = [];
    foreach ($urls as $url) {
        if (sanitizeUrl($url)) {
            $validUrls[] = $url;
        }
    }
    return $validUrls;
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

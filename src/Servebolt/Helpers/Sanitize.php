<?php

namespace Servebolt\SDK\Helpers;

function sanitizeUrl(string $url) : string
{
    $urlParts = parse_url($url);
    // TODO: Throw exception if failed
    return $url;
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

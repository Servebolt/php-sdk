<?php

namespace Servebolt\SDK\Helpers;

function sanitizeUrl(string $url) : string
{
    $urlParts = parse_url($url);
    return $url;
}

function sanitizeDomain(string $domain, bool $allowPath = true, bool $allowQueryString = false) : string
{
    return $domain;
}

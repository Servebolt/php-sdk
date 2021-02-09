<?php

namespace Servebolt\Sdk\Helpers;

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

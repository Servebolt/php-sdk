<?php

namespace Servebolt\Sdk\Endpoints;

use Servebolt\Sdk\Response;

abstract class Endpoint
{

    /**
     * Conditional format on HTTP response.
     *
     * @param $httpResponse
     * @return object|Response
     */
    protected function response($httpResponse)
    {
        if ($this->config->get('returnPsr7Response', false)) {
            return $httpResponse->getDecodedBody();
        }
        if (property_exists($this, 'modelBinding') && $this->modelBinding) {
            return new Response($httpResponse->getDecodedBody(), $this->modelBinding);
        }
        return new Response($httpResponse->getDecodedBody());
    }
}

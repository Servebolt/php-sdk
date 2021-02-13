<?php

namespace Servebolt\Sdk\Endpoints;

use Servebolt\Sdk\Response;
use GuzzleHttp\Psr7\Response as Psr7Response;

abstract class Endpoint
{

    /**
     * Conditional format on HTTP response.
     *
     * @param $httpResponse
     * @return object|Response|Psr7Response
     */
    protected function response($httpResponse)
    {
        switch ($this->config->get('responseObjectType')) {
            case 'psr7':
                return $httpResponse->getResponseObject();
            case 'rawObject':
                return $httpResponse->getDecodedBody();
            case 'customResponse':
            default:
                return new Response($httpResponse->getDecodedBody(), $this->getModelBinding());
        }
    }

    /**
     * @return null|string
     */
    private function getModelBinding()
    {
        if (property_exists($this, 'modelBinding') && $this->modelBinding) {
            return $this->modelBinding;
        }
    }
}

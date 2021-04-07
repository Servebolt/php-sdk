<?php

namespace Servebolt\Sdk\Exceptions;

use ServeboltOptimizer_Vendor\GuzzleHttp\Exception\ClientException;
use Servebolt\Sdk\Response;

class ServeboltHttpClientException extends ClientException
{

    public function getDecodeMessage() : object
    {
        return json_decode($this->getResponse()->getBody()->getContents());
    }

    public function getResponseObject() : Response
    {
        return new Response($this->getDecodeMessage(), $this->getResponse()->getStatusCode());
    }
}

<?php

namespace Servebolt\Sdk\Exceptions;

use GuzzleHttp\Exception\ClientException;
use Servebolt\Sdk\Helpers\Response;

class ServeboltHttpClientException extends ClientException
{

    public function getDecodeMessage() : object
    {
        return json_decode($this->getResponse()->getBody()->getContents());
    }

    public function getResponseObject() : Response
    {
        return new Response($this->getDecodeMessage());
    }
}

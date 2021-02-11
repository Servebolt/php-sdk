<?php

namespace Servebolt\Sdk\Exceptions;

use GuzzleHttp\Exception\ClientException;
use Servebolt\Sdk\Helpers\Response as ServeboltResponse;

class ServeboltHttpClientException extends ClientException
{

    public function getDecodeMessage() : object
    {
        return json_decode($this->getResponse()->getBody()->getContents());
    }

    public function getResponseObject()
    {
        return new ServeboltResponse($this->getDecodeMessage());
    }
}

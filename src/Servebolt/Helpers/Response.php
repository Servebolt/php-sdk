<?php

namespace Servebolt\Sdk\Helpers;

class Response
{

    private object $responseData;
    private string $modelClass;
    private bool $isMultiple = false;
    private array $items = [];

    public function __construct($httpResponse, $modelClass = null)
    {
        $this->responseData = $httpResponse->getData();
        $this->modelClass = $modelClass;
        $this->parseData();
        /*
        print_r($this->responseData);
        die;
        */
    }

    private function parseData()
    {
        $result = $this->responseData->result ?? null;
        if (is_array($result) && is_subclass_of($this->modelClass, 'Servebolt\\Sdk\\Models\\Model')) {
            $this->isMultiple = true;
            $this->items = array_map(function ($item) {
                return new $this->modelClass($item);
            }, $result);
        } else {
            $this->items[] = $result;
        }
    }

    private function parseResult()
    {
    }
}

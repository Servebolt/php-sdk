<?php

namespace Servebolt\Sdk\Helpers;

use Servebolt\Sdk\Traits\HasErrors;
use Servebolt\Sdk\Traits\HasMessages;

class Response
{

    use HasErrors, HasMessages;

    /**
     * @var string|mixed The (optional) model that the items should be converted to.
     */
    private string $modelClass;

    /**
     * @var object The response data from the HTTP request.
     */
    private object $responseData;

    /**
     * @var bool Whether the request resulted in a success.
     */
    private bool $success;

    /**
     * @var bool Whether the response contains multiple items in the data set.
     */
    private bool $isMultiple;

    /**
     * @var array|object|null The extracted data from the HTTP response, typically containing the items.
     */
    private $data = null;

    /**
     * Response constructor.
     * @param $responseData
     * @param null $modelClass
     */
    public function __construct($responseData, $modelClass = null)
    {
        $this->responseData = $responseData;
        if ($modelClass) {
            // The model that should be used with the items in the response data (if any)
            $this->modelClass = $modelClass;
        }
        $this->parseData();
    }

    /**
     * Whether this response contains multiple items in the response data.
     *
     * @return bool
     */
    public function hasMultiple() : bool
    {
        return $this->isMultiple === true;
    }

    /**
     * Whether the request was successful or not.
     *
     * @return bool
     */
    public function wasSuccessful() : bool
    {
        return $this->success === true;
    }

    /**
     * @return array|object|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Whether we have data present in this response.
     *
     * @return bool
     */
    public function hasData() : bool
    {
        if (is_null($this->data)) {
            return false;
        }
        return true;
    }

    /**
     * @return null|object
     */
    public function getFirstItem()
    {
        if ($this->hasMultiple() && $this->hasData()) {
            $data = $this->getData();
            if (is_array($data) && !empty($data)) {
                return current($data);
            }
        }
    }

    private function parseData() : void
    {
        $this->success = $this->responseData->success;
        $this->parseResult();
        $this->parseMessages();
        $this->parseErrors();
    }

    private function parseResult() : void
    {
        if (isset($this->responseData->result)) {
            if (is_array($this->responseData->result)) {
                $this->isMultiple = true;
                if (isset($this->modelClass) && is_subclass_of($this->modelClass, 'Servebolt\\Sdk\\Models\\Model')) {
                    $this->data = array_map(function ($item) {
                        return new $this->modelClass($item);
                    }, $this->responseData->result);
                } else {
                    $this->data = $this->responseData->result;
                }
            } else {
                $this->isMultiple = false;
                $this->data = $this->responseData->result;
            }
        } else {
            $this->isMultiple = false;
            // This response has no result-data
        }
    }

    private function parseMessages() : void
    {
        if (!property_exists($this, 'messages')) {
            return;
        }
        if (isset($this->responseData->messages) && is_array($this->responseData->messages)) {
            $this->messages = $this->responseData->messages;
        }
    }

    private function parseErrors() : void
    {
        if (!property_exists($this, 'errors')) {
            return;
        }
        if (isset($this->responseData->errors) && is_array($this->responseData->errors)) {
            $this->errors = $this->responseData->errors;
        }
    }
}

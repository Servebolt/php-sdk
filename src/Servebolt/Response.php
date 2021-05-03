<?php

namespace Servebolt\Sdk;

use Servebolt\Sdk\Traits\HasErrors;
use Servebolt\Sdk\Traits\HasMessages;

class Response
{

    use HasErrors, HasMessages;

    /**
     * @var string|mixed The (optional) model that the items should be converted to.
     */
    private $modelClass;

    /**
     * @var object The response body object from the HTTP request.
     */
    private $responseBody;

    /**
     * @var int The response body object from the HTTP request.
     */
    private $httpStatusCode;

    /**
     * @var bool Whether the request resulted in a success.
     */
    private $success;

    /**
     * @var bool Whether the response contains multiple items in the result data set.
     */
    private $isMultiple;

    /**
     * @var array|object|null The extracted result data from the HTTP response.
     */
    private $result = null;

    /**
     * Response constructor.
     * @param object $responseBody
     * @param int $httpStatusCode
     * @param string|null $modelClass
     */
    public function __construct(object $responseBody, $httpStatusCode = null, $modelClass = null)
    {
        $this->responseBody = $responseBody;
        if (is_int($httpStatusCode)) {
            $this->httpStatusCode = $httpStatusCode;
        }
        if ($modelClass) {
            // The model that should be used with the items in the result data (if any)
            $this->modelClass = $modelClass;
        }
        $this->parseResponseBody();
    }

    /**
     * @return int|null
     */
    public function getStatusCode()
    {
        if (isset($this->httpStatusCode)) {
            return $this->httpStatusCode;
        }
    }

    /**
     * @return false|mixed|string
     */
    private function getModelClassName()
    {
        if ($this->modelClass) {
            $parts = explode('\\', $this->modelClass);
            return end($parts);
        }
        return false;
    }

    /**
     * @param $name
     * @param $arguments
     * @return array|false|object|null
     */
    public function __call($name, $arguments)
    {
        if ($className = $this->getModelClassName()) {
            if ($name === 'get' . $className . 's') {
                if ($this->hasMultiple()) {
                    return $this->getResult();
                }
                return false;
            }
            if ($name === 'get' . $className) {
                return $this->getFirstResultItem();
            }
        }
        trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
    }

    /**
     * @return null|object
     */
    public function getRawResponse() : object
    {
        return $this->responseBody;
    }

    /**
     * Whether this response contains multiple items in the response result data.
     *
     * @return bool
     */
    public function hasMultiple() : bool
    {
        return $this->isMultiple === true;
    }

    /**
     * An alias of the "hasMultiple"-method.
     *
     * @return bool
     */
    public function isIterable() : bool
    {
        return $this->hasMultiple();
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
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Whether we have result data present in this response.
     *
     * @return bool
     */
    public function hasResult() : bool
    {
        if (is_null($this->result)) {
            return false;
        }
        return true;
    }

    /**
     * @return int
     */
    public function countResultItems() : int
    {
        if ($resultItems = $this->getResultItems()) {
            return count($resultItems);
        }
        return 0;
    }

    /**
     * @return array|object|void
     */
    public function getResultItems()
    {
        if ($this->hasMultiple()) {
            $result = $this->getResult();
            if (is_array($result)) {
                return $result;
            }
        }
    }

    /**
     * @return null|object
     */
    public function getFirstResultItem()
    {
        if ($this->hasResult()) {
            $result = $this->getResult();
            if ($this->hasMultiple()) {
                if (is_array($result)) {
                    return current($result);
                }
            } else {
                return $result;
            }
        }
    }

    private function parseResponseBody() : void
    {
        $this->parseSuccessState();
        $this->parseResult();
        $this->parseMessages();
        $this->parseErrors();
    }

    private function parseSuccessState() : void
    {
        //$this->success = $this->responseBody->success ?? false;
        //$this->success = (bool) preg_match('/^20/', );
        // Make sure that the HTTP status code is in the 200-range
        $this->success = substr($this->httpStatusCode, 0, 2) == '20';
    }

    private function parseResult() : void
    {
        if (isset($this->responseBody->result)) {
            if (is_array($this->responseBody->result)) {
                $this->isMultiple = true;
                if (isset($this->modelClass) && is_subclass_of($this->modelClass, 'Servebolt\\Sdk\\Models\\Model')) {
                    $this->result = array_map(function ($item) {
                        return new $this->modelClass($item, true);
                    }, $this->responseBody->result);
                } else {
                    $this->result = $this->responseBody->result;
                }
            } else {
                $this->isMultiple = false;
                $this->result = $this->responseBody->result;
            }
        } else {
            $this->isMultiple = false;
            // This response has no result in the response body
        }
    }

    private function parseMessages() : void
    {
        if (!property_exists($this, 'messages')) {
            return;
        }
        if (isset($this->responseBody->messages) && is_array($this->responseBody->messages)) {
            $this->messages = $this->responseBody->messages;
        }
    }

    private function parseErrors() : void
    {
        if (!property_exists($this, 'errors')) {
            return;
        }
        if (isset($this->responseBody->errors) && is_array($this->responseBody->errors)) {
            $this->errors = $this->responseBody->errors;
        }
    }
}

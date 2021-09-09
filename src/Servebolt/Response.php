<?php

namespace Servebolt\Sdk;

use Servebolt\Sdk\Traits\HasErrors;
use Servebolt\Sdk\Traits\HasMessages;

/**
 * Class Response
 * @package Servebolt\Sdk
 */
class Response
{

    use HasErrors, HasMessages;

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
     */
    public function __construct($responseBody, $httpStatusCode = null)
    {
        $this->responseBody = $responseBody;
        if (is_int($httpStatusCode)) {
            $this->httpStatusCode = $httpStatusCode;
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
     * Alias for "getFirstResultItem".
     *
     * @return null|object
     */
    public function getResultItem()
    {
        return $this->getFirstResultItem();
    }

    /**
     * Get the first item in results.
     *
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

    /**
     * Parse the result body by best effort.
     */
    private function parseResult(): void
    {
        if (isset($this->responseBody->data)) {
            $this->isMultiple = is_array($this->responseBody->data);
            $this->result = $this->responseBody->data; // Return plain result
        } else {
            // This response has no result in the response body
            $this->isMultiple = false;
        }
    }

    private function parseMessages() : void
    {
        if (!property_exists($this, 'messages')) {
            return;
        }
        if (
            isset($this->responseBody->messages)
            && is_array($this->responseBody->messages)
        ) {
            $this->setMessages($this->responseBody->messages);
        }
    }

    private function parseErrors() : void
    {
        if (!property_exists($this, 'errors')) {
            return;
        }
        if (
            isset($this->responseBody->errors)
            && is_array($this->responseBody->errors)
        ) {
            $this->setErrors($this->responseBody->errors);
        }
    }
}

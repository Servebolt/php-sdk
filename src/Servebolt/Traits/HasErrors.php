<?php

namespace Servebolt\Sdk\Traits;

trait HasErrors
{

    private $errors = [];

    public function hasErrors() : bool
    {
        return ! empty($this->errors);
    }

    public function hasSingleError() : bool
    {
        return $this->hasErrors() && count($this->getErrors()) === 1;
    }

    public function getErrors() : array
    {
        return $this->errors;
    }

    /**
     * @return null|object
     */
    public function getFirstError()
    {
        if ($this->hasErrors()) {
            return current($this->getErrors());
        }
    }

    /**
     * @return null|string
     */
    public function getFirstErrorMessage()
    {
        if ($error = $this->getFirstError()) {
            return $error->message;
        }
    }

    /**
     * @return null|string
     */
    public function getFirstErrorCode()
    {
        if ($error = $this->getFirstError()) {
            return $error->code;
        }
    }
}

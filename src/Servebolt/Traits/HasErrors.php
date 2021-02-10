<?php

namespace Servebolt\Sdk\Traits;

trait HasErrors
{

    private array $errors = [];

    public function hasErrors() : bool
    {
        return count($this->errors) > 0;
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
}

<?php

namespace Servebolt\Sdk\Traits;

trait MethodToPropertyAccessor
{

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }
        trigger_error('Call to undefined method ' . __CLASS__ . '::' . $name . '()', E_USER_ERROR);
    }
}

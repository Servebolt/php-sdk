<?php

namespace Servebolt\Sdk\Traits;

trait ModelFactoryTrait
{
    public static function factory($array)
    {
        return new self($array);
    }
}

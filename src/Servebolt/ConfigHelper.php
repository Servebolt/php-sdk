<?php

namespace Servebolt\SDK;

/**
 * Class ConfigHelper
 * @package Servebolt\SDK
 */
class ConfigHelper
{

    /**
     * @var array
     */
    private array $configArray = [];

    /**
     * @param array $array
     * @param false $append
     */
    public function setWithArray(array $array, $append = false) : void
    {
        if ($append) {
            $this->configArray = $this->configArray + $array;
        } else {
            $this->configArray = $array;
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value) : void
    {
        $this->configArray[$key] = $value;
    }

    /**
     * @param string $key
     * @param null|mixed $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->configArray)) {
            return $this->configArray[$key];
        }
        return $default;
    }
}

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
    private $configArray = [];

    /**
     * @param $array
     * @param false $append
     */
    public function setWithArray($array, $append = false)
    {
        if ($append) {
            $this->configArray = $this->configArray + $array;
        } else {
            $this->configArray = $array;
        }
    }

    /**
     * @param $key
     * @param null $value
     */
    public function set($key, $value) : void
    {
        $this->configArray[$key] = $value;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->configArray)) {
            return $this->configArray[$key];
        }
        return $default;
    }
}

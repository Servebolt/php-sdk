<?php

namespace Servebolt\SDK;

/**
 * Class ConfigHelper
 * @package Servebolt\SDK
 */
class ConfigHelper
{

    /**
     * An array containing the configuration items.
     *
     * @var array
     */
    private array $configArray = [];

    /**
     * Set the configuration using an associative array.
     *
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
     * Set a configuration item.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value) : void
    {
        $this->configArray[$key] = $value;
    }

    /**
     * Get a configuration item.
     *
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

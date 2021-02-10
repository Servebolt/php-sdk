<?php

namespace Servebolt\SDK\Models;

abstract class Model
{

    protected $propertyValues = [];

    private bool $isHydrated = false;

    /**
     * Model constructor.
     * @param array|object $modelData
     */
    public function __construct($modelData = [])
    {
        if (!empty($modelData)) {
            $this->hydrate($modelData);
        }
    }

    public function isHydrated() : bool
    {
        return $this->isHydrated === true;
    }

    /**
     * @param $name
     * @return null|mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->propertyValues)) {
            $value = $this->propertyValues[$name];
            if (in_array($name, array_keys($this->casts))) {
                settype($value, $this->casts[$name]); // Cast variable to type
            }
            return $value;
        }
        trigger_error(sprintf('Undefined property: $%s', $name), E_USER_NOTICE);
        return null;
    }

    /**
     * @param array|object $modelData
     * @return bool
     */
    public function hydrate($modelData) : bool
    {
        if (is_object($modelData)) {
            $modelData = (array) $modelData;
        }
        if (empty($modelData)) {
            return false;
        }
        foreach ($this->properties as $property) {
            if (array_key_exists($property, $modelData)) {
                $this->propertyValues[$property] = $modelData[$property];
            }
        }
        $this->isHydrated = true;
        return true;
    }
}

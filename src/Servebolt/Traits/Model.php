<?php

namespace Servebolt\SDK\Traits;

trait Model {

    private bool $isHydrated = false;

    /**
     * Model constructor.
     * @param array $modelData
     */
    public function __construct(array $modelData = [])
    {
        if (!empty($modelData)) {
            $this->hydrate($modelData);
        }
    }

    public function isHydrated() : bool
    {
        return $this->isHydrated === true;
    }

    public function hydrate(array $modelData = []) : bool
    {
        if (empty($modelData)) {
            return false;
        }
        foreach ($this->properties as $property)
        {
            if (array_key_exists($property, $modelData)) {
                $value = $modelData[$property];
                if (array_key_exists($property, array_keys($this->casts))) {
                    settype($value, $this->casts[$property]); // Cast variable to type
                }
                $this->{$property} = $value;
            }
        }
        $this->isHydrated = true;
        return true;
    }

}

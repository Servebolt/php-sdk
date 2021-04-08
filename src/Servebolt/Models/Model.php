<?php

namespace Servebolt\Sdk\Models;

use Servebolt\Sdk\Exceptions\ServeboltInvalidModelDataException;
use Servebolt\Sdk\Exceptions\ServeboltActionObjectIsNotAnEndpoint;
use Servebolt\Sdk\Response;

abstract class Model
{

    protected $properties = [];
    protected $propertyValues = [];
    protected $requiredPropertiesOnCreation = [];
    protected $guardedProperties = ['id'];
    protected $casts = [];

    private $throwExceptionOnInvalidData;
    private $modelDataValid = true;
    private $isHydrated = false;
    private $isPersisted = false;

    /**
     * Model constructor.
     * @param array|object $modelData
     * @param bool $isPersisted
     * @param bool $throwExceptionOnInvalidData
     */
    public function __construct($modelData = [], $isPersisted = false, $throwExceptionOnInvalidData = false)
    {
        $this->throwExceptionOnInvalidData = $throwExceptionOnInvalidData;
        if ($isPersisted) {
            $this->isPersisted = $isPersisted;
        }
        if (!empty($modelData)) {
            $this->hydrate($modelData);
        }
    }

    public function isPersisted() : bool
    {
        return $this->isPersisted === true;
    }

    /**
     * Alias of mehod "persist".
     *
     * @param object|null $endpointObject
     * @return Response
     * @throws ServeboltActionObjectIsNotAnEndpoint
     */
    public function save(?object $endpointObject = null) : Response
    {
        return $this->persist($endpointObject);
    }

    /**
     * @return null|string
     */
    private function getEndpointBinding()
    {
        $class = get_class($this);
        if (property_exists($class, 'endpointBinding') && $class::$endpointBinding) {
            return $class::$endpointBinding;
        }
    }

    private function resolveEndpointObject()
    {
        if ($endpointBinding = $this->getEndpointBinding()) {
            $client = \Servebolt\Sdk\Client::getInstance();
            //var_dump($client);die;
            // TODO: Loop through all the endpoints, find the endpoint that has a binding to this model, return an instance of that endpoint
        }
        return null;
    }

    /**
     * @param object|null $endpointObject
     * @return Response
     * @throws ServeboltActionObjectIsNotAnEndpoint
     */
    public function persist(?object $endpointObject = null) : Response
    {
        if (is_null($endpointObject)) {
            $endpointObject = $this->resolveEndpointObject();
        }
        if (!is_subclass_of($endpointObject, '\\Servebolt\\Sdk\\Endpoints\\Endpoint')) {
            throw new ServeboltActionObjectIsNotAnEndpoint(
                'You\'re trying to persist a model with an object that is not an instance of an endpoint-class.'
            );
        }
        if ($this->isPersisted()) {
            return $endpointObject->replace($this);
        } else {
            $response = $endpointObject->create($this);
            if ($response->wasSuccessful()) {
                $this->isPersisted = true;
            }
            return $response;
        }
    }

    public function isValid() : bool
    {
        return $this->modelDataValid === true;
    }

    public function isHydrated() : bool
    {
        return $this->isHydrated === true;
    }

    public function getCasts() : array
    {
        return $this->casts;
    }

    /**
     * @param $name
     * @return null|mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->propertyValues)) {
            $value = $this->propertyValues[$name];
            $casts = $this->getCasts();
            if (!empty($casts) && in_array($name, array_keys($casts))) {
                settype($value, $casts[$name]); // Cast variable to type
            }
            return $value;
        }
        trigger_error(sprintf('Undefined property: $%s', $name), E_USER_NOTICE);
        return null;
    }

    public function getPropertyValues() : array
    {
        return $this->propertyValues;
    }

    public function toSnakeCase() : array
    {
        return array_flip(array_map(function ($item) {
            return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $item));
        }, array_flip($this->getPropertyValues())));
    }

    public function toArray() : array
    {
        return $this->getPropertyValues();
    }

    /**
     * @param array|object $modelData
     * @return bool
     */
    public function hydrate($modelData) : bool
    {
        if (is_a($modelData, get_class($this))) {
            $modelData = $modelData->getPropertyValues();
        }
        if (is_object($modelData)) {
            $modelData = (array) $modelData;
        }
        if (empty($modelData)) {
            return false;
        }
        $this->populateProperties($modelData);
        $this->validateHydration();
        $this->isHydrated = true;
        return true;
    }

    public function getModelProperties() : array
    {
        return $this->properties;
    }

    private function populateProperties($modelData) : void
    {
        foreach ($this->getModelProperties() as $property) {
            if (!$this->isPersisted() && in_array($property, $this->guardedProperties)) {
                continue; // Don't let users set guarded properties unless we are working with a persisted model
            }
            if (array_key_exists($property, $modelData)) {
                $this->propertyValues[$property] = $modelData[$property];
            }
        }
    }

    public function getRequiredPropertiesOnCreation() : array
    {
        return $this->requiredPropertiesOnCreation;
    }

    private function validateHydration() : void
    {
        if (!empty($this->getRequiredPropertiesOnCreation())) {
            foreach ($this->getRequiredPropertiesOnCreation() as $requiredProperty) {
                if (!array_key_exists($requiredProperty, $this->propertyValues)) {
                    $this->modelDataValid = false;
                    if ($this->throwExceptionOnInvalidData) {
                        throw new ServeboltInvalidModelDataException(
                            sprintf('Model is missing field %s', $requiredProperty)
                        );
                    }
                }
            }
        }
    }
}

<?php

namespace App\ProjectElements\Models;

use App\ProjectHelpers\Files\ClassHelper;
use App\ProjectInterfaces\ModelInterface;
use Exception;

class GenericModel implements ModelInterface
{

    public function __call($method, $arguments) 
    {
        return ClassHelper::handleUnknownClassCall(
            $this,
            $method,
            $arguments
        );
    }

    public function handleParentCall(string $method, array $arguments)
    {
        throw new Exception("Attempted to call method $method");
    }

    public function handleGetterMethod(string $property)
    {
        return ClassHelper::handleGenericGetCall(
            $this,
            $property
        );
    }
    
    public function handleSetterMethod(string $property, $value) 
    {
        return ClassHelper::handleGenericSetCall(
            $this,
            $property,
            $value
        );
    }

    public function handleGenericGet(string $property)
    {
        $valueOfProperty = $this->$property;
        return $valueOfProperty;
    }

    public function handleRelationshipGet(string $relationshipJson)
    {
        throw new Exception(sprintf(
            "No strategy for relationship get implemented on",
            get_class($this)
        ));
    }

    public function handleGenericSet(string $property, $value)
    {
        $this->$property = $value;
        return $this;
    }

    public function handleRelationShipSet(string $relationshipJson, $value)
    {
        throw new Exception(sprintf(
            "No strategy for relationship set implemented on",
            get_class($this)
        ));
    }
}
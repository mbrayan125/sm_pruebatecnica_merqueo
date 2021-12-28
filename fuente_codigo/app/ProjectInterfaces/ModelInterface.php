<?php

namespace App\ProjectInterfaces;

interface ModelInterface
{
    public function __call(string $method, array $arguments);
    public function handleParentCall(string $method, array $arguments);
    public function handleGetterMethod(string $property);
    public function handleSetterMethod(string $property, $value);
    public function handleGenericGet(string $property);
    public function handleRelationshipGet(string $relationshipJson);
    public function handleGenericSet(string $property, $value);
    public function handleRelationshipSet(string $relationshipJson, $property, $value);
}
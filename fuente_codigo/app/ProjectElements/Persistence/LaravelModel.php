<?php

namespace App\ProjectElements\Persistence;

use Exception;
use Illuminate\Database\Eloquent\Model;

abstract class LaravelModel extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * \@pendiente_documentacion_stevin
     *
     * @param [type] $method
     * @param [type] $arguments
     *
     * @return void
     * @throws [type] Cuando ocurra alguna excepción en el procesamiento
     *
     * @author Stiven Mamián <brayan.mamian@makrosoft.co>
     */
    public function __call($method, $arguments) 
    {
        $positionGet = strpos($method, "get");
        if ($positionGet !== false && $positionGet == 0) {
            return $this->handleGetterMethod($method);
        }

        $positionSet = strpos($method, "set");
        if ($positionSet !== false && $positionSet == 0) {
            return $this->handleSetterMethod($method, $arguments);
        }   

        return parent::__call($method, $arguments);
    }

    /**
     * \@pendiente_documentacion_stevin
     *
     * @param [type] $methodName
     *
     * @return string
     * @throws [type] Cuando ocurra alguna excepción en el procesamiento
     *
     * @author Stiven Mamián <brayan.mamian@makrosoft.co>
     */
    private function checkGetAndSetProperty($methodName) : string
    {
        $property = lcfirst(substr($methodName, 3));
        if (!property_exists($this, $property)) {
            throw new Exception("Attempted to call get/set method over a non-existent property $property");
        }
        return $property;
    }

    /**
     * \@pendiente_documentacion_stevin
     *
     * @param [type] $method
     *
     * @return void
     * @throws [type] Cuando ocurra alguna excepción en el procesamiento
     *
     * @author Stiven Mamián <brayan.mamian@makrosoft.co>
     */
    private function handleGetterMethod($method)
    {
        $property = $this->checkGetAndSetProperty($method);
        $valueOfProperty = $this->attributes[$property];
        $this->$property = $valueOfProperty;
        return $valueOfProperty;
    }

    /**
     * \@pendiente_documentacion_stevin
     *
     * @param string $method
     * @param [type] $arguments
     *
     * @return void
     * @throws [type] Cuando ocurra alguna excepción en el procesamiento
     *
     * @author Stiven Mamián <brayan.mamian@makrosoft.co>
     */
    private function handleSetterMethod(string $method, $arguments) 
    {
        $argumentsSize = sizeof($arguments);
        if ($argumentsSize != 1) {
            throw new Exception(sprintf("Expected 1 parameter to setter method %d given", $argumentsSize));
        }
        $property = $this->checkGetAndSetProperty($method);
        $valueProperty = $arguments[0];
        $this->attributes[$property] = $valueProperty;
        $this->$property = $valueProperty;
        return $this;
    }
}

<?php

namespace App\ProjectElements\Models;

use App\ProjectElements\AppDispatcher;
use App\ProjectHelpers\Files\ClassHelper;
use App\ProjectInterfaces\ModelInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;

abstract class LaravelModel extends Model implements ModelInterface
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function __call($method, $arguments) {

        return ClassHelper::handleUnknownClassCall(
            $this,
            $method,
            $arguments
        );
    }

    public function handleParentCall(string $method, array $arguments)
    {
        return parent::__call($method, $arguments);
    }

    public function handleGetterMethod($property)
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
        $valueOfProperty = $this->attributes[$property];
        $this->$property = $valueOfProperty;
        return $valueOfProperty;
    }

    public function handleRelationshipGet(string $relationshipJson)
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();

        $realationship = json_decode($relationshipJson);
        if (! $realationship) {
            throw new Exception("Incorrect syntax for relationship definition");
        }
            
        $selfPropertyGet = "get" . $realationship->mappedBy;
        $selfProperty = $this->__call($selfPropertyGet, []);

        if (is_null($selfProperty)) {
            return null;
        }

        $relatedClass = $persistenceManager::findBy(
            $realationship->targetClass,
            array(
                [ $realationship->inversedBy, "=",  $selfProperty]
            )
        );

        if ($realationship->type == "many_to_one" || $realationship->type == "one_to_one") {
            if (sizeof($relatedClass) != 1) {
                throw new Exception(sprintf(
                    "Cannot resolve %s association, expected 1 classes of %s, %d found",
                    $realationship->type,
                    $realationship->targetClass,
                    sizeof($relatedClass)
                ));
            }   
            return $relatedClass[0];
        }

        if ($realationship->type == "one_to_many") {
            return $relatedClass;
        }
    }

    public function handleGenericSet(string $property, $value)
    {
        $this->attributes[$property] = $value;
        $this->$property = $value;
        return $this;
    }

    public function handleRelationshipSet(string $relationshipJson, $property, $value)
    {
        $realationship = json_decode($relationshipJson);
        if (! $realationship) {
            throw new Exception("Incorrect syntax for relationship definition");
        }

        if ($realationship->type == "one_to_many") {
            throw new Exception("Cannot use set on terminal one of OneToMany relationship");
        }

        $continueSearch = true;
        $foreignValue = null;

        if (is_null($value)) {
            $continueSearch = false;
        }

        if ($continueSearch && (is_integer($value) || ctype_digit($value))) {
            $foreignValue = intval($value);
            $continueSearch = false;
        }
     
        if ($continueSearch && is_object($value)) {

            if (is_a($value, $realationship->targetClass)) {
                $foreignValue = $value->getId();
                $continueSearch = false;
            }
        }

        if (is_null($foreignValue) && $continueSearch) {
            throw new Exception(sprintf(
                "%s Data type %s invalid for set relationship %s option on set %s",
                get_class($this),
                is_object($value) ? get_class($value) : gettype($value),
                $realationship->targetClass,
                $property
            ));
        }

        return $this->handleGenericSet(
            $realationship->mappedBy,
            $foreignValue
        );
    }
    
}

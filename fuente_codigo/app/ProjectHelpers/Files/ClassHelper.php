<?php

namespace App\ProjectHelpers\Files;

use App\ProjectInterfaces\ModelInterface;
use Exception;
use ReflectionClass;
use ReflectionProperty;

abstract class ClassHelper
{
    public static function getVarClassComment(
        string $classPath,
        string $propertyName,
        string $annotationSearch
    ) {
        $reflecionClass = new ReflectionClass($classPath);
        $valueProperty = null;

        if ($reflecionClass->hasProperty($propertyName)) {
            $reflectionProperty = new ReflectionProperty($classPath, $propertyName);
            $docComments = $reflectionProperty->getDocComment();
            preg_match_all('#@(.*?)\n#s', $docComments, $annotations);
            $docComments = $annotations[1];

            foreach ($docComments as $docComment) {
                $docCommentSlices = explode(" ", $docComment);
                if (sizeof($docCommentSlices) < 2) {
                    continue;
                }
                $sliceProperty = $docCommentSlices[0];
                if ($sliceProperty == $annotationSearch) {
                    $valueProperty = implode(" ", array_slice($docCommentSlices, 1));
                    break;
                }
            }
        }
        return $valueProperty;
    }    

    public static function handleUnknownClassCall(
        ModelInterface $entity,
        string $method,
        array $arguments
    ) {
        $positionGet = strpos($method, "get");
        if ($positionGet !== false && $positionGet == 0) {
            $property = self::getPropertyFromMethod(
                $entity, 
                $method, 
                "GET"
            );
            return $entity->handleGetterMethod($property);
        }

        $positionSet = strpos($method, "set");
        if ($positionSet !== false && $positionSet == 0) {
            $argumentsSize = sizeof($arguments);
            if ($argumentsSize != 1) {
                throw new Exception(sprintf(
                    "Expected 1 parameter to invoke setter method %d given", 
                    $argumentsSize
                ));
            }
            $property = self::getPropertyFromMethod(
                $entity, 
                $method, 
                "SET"
            );
            return $entity->handleSetterMethod($property, $arguments[0]);
        }   

        return $entity->handleParentCall($method, $arguments);
    }

    public static function handleGenericGetCall(
        ModelInterface $entity,
        string $property
    ) {
        $relationshipInfo = self::getVarClassComment(
            get_class($entity),
            $property,
            "relationship"
        );

        if ($relationshipInfo) {
            return $entity->handleRelationShipGet($relationshipInfo);
        }

        return $entity->handleGenericGet($property);
    }

    public static function handleGenericSetCall(
        ModelInterface $entity,
        string $property,
        $value
    ) {
        $relationshipInfo = self::getVarClassComment(
            get_class($entity),
            $property,
            "relationship"
        );

        if ($relationshipInfo) {
            return $entity->handleRelationshipSet($relationshipInfo, $value);
        }

        return $entity->handleGenericSet($property, $value);
    }


    private static function getPropertyFromMethod(
        ModelInterface $entity,
        $methodName,
        $methodType
    ): string {
        $property = lcfirst(substr($methodName, 3));
        $mandatoryProperty = true;
        if (strlen($property) > 3) {
            $propertyEnding = substr($property, -3);
            if ($propertyEnding == "_id") {
                $mandatoryProperty = false;
            }
        }
        if ($mandatoryProperty && !property_exists($entity, $property)) {
            throw new Exception(sprintf(
                "Attempted to call %s method over a non-existent property %s",
                $methodType,
                $property
            ));
        }
        return $property;
    }
}
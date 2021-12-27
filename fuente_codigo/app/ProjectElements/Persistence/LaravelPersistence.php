<?php

namespace App\ProjectElements\Persistence;

use App\ProjectInterfaces\PersistenceInterface;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Iterator;
use Traversable;

class LaravelPersistence implements PersistenceInterface
{
    public static function retrieveEntities(
        string $classPath, 
        array $parameters = array(),
        array $orderBy = array(),
        int $limit = PHP_INT_MAX,
        int $page = 1
    ) {

        $classReference = new $classPath;
        self::validateLaravelEntity($classReference);
        $query = $classReference::where($parameters);
        return $query->get();
    }

    public static function saveEntity(
        object $entity
    ) : void {

        self::validateLaravelEntity($entity);
        $entity->save();
    }


    private static function validateLaravelEntity (object $entity) 
    {
        if (!$entity instanceof Model) {
            throw new Exception("Can't use laravel persistence over not laravel class/object");
        }
    }
}
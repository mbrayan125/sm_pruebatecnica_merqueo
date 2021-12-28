<?php

namespace App\ProjectElements\Persistence;

use App\ProjectInterfaces\PersistenceInterface;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class EloquentPersistence implements PersistenceInterface
{
    public static function findBy(
        string $classPath, 
        array $parameters = array(),
        array $orderBy = array(),
        int $limit = PHP_INT_MAX,
        int $page = 1
    ) {

        $classReference = new $classPath;
        self::validateLaravelEntity($classReference);
        $query = $classReference::where($parameters)
            ->take($limit);
        foreach ($orderBy as $order) {
            $query->orderBy($order[0], $order[1]);
        }
        return $query->get();
    }

    public static function findOneBy(
        string $classPath, 
        array $parameters = array(),
        array $orderBy = array()
    ): ?object {
        
        $entity = null;
        $entities = self::findBy(
            $classPath,
            $parameters,
            $orderBy,
            1
        );

        if ($entities && sizeof($entities) > 0) {
            if (is_array($entities)) {
                $entity = $entities[0];
            }
            if ($entities instanceof Collection) {
                $entity = $entities->first();
            }
        }

        return $entity;
    }

    public static function saveEntity(
        object $entity
    ) : void {

        self::validateLaravelEntity($entity);
        $entity->save();
        $entity->fresh();
    }

    public static function refreshEntity(object $entity): void
    {
        self::validateLaravelEntity($entity);
        $entity->fresh();
    }


    private static function validateLaravelEntity (object $entity) 
    {
        if (!$entity instanceof Model) {
            throw new Exception("Can't use laravel persistence over not laravel class/object");
        }
    }
}
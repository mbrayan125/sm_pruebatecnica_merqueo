<?php

namespace App\ProjectElements\Persistence;

use App\ProjectInterfaces\PersistenceInterface;
use stdClass;

class MemoryPersistence implements PersistenceInterface
{
    public static function findBy(
        string $classPath,
        array $parameters = array(),
        array $orderBy = array(),
        int $limit = PHP_INT_MAX,
        int $page = 1
    ) {

        return array();
    }
    
    public static function findOneBy(
        string $classPath, 
        array $parameters = array(),
        array $orderBy = array()
    ): ?object {
        
        return null;
    }

    public static function saveEntity(
        object $entity
    ): void {
        
    }

    public static function refreshEntity(object $entity): void
    {
        
    }
}
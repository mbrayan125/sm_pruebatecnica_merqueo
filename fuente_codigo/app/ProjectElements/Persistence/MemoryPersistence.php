<?php

namespace App\ProjectElements\Persistence;

use App\ProjectInterfaces\PersistenceInterface;

class MemoryPersistence implements PersistenceInterface
{
    public static function retrieveEntities(
        string $classPath,
        array $parameters = array(),
        array $orderBy = array(),
        int $limit = PHP_INT_MAX,
        int $page = 1
    ) {

        return array();
    }

    public static function saveEntity(
        object $entity
    ): void {
        
    }
}
<?php

namespace App\ProjectInterfaces;

use Iterator;
use Traversable;

interface PersistenceInterface
{
    public static function findBy(
        string $classPath, 
        array $parameters = array(),
        array $orderBy = array(),
        int $limit = PHP_INT_MAX,
        int $page = 1
    );

    public static function findOneBy(
        string $classPath, 
        array $parameters = array(),
        array $orderBy = array()
    ): ?object;

    public static function saveEntity(
        object $entity
    ) : void;

    public static function refreshEntity(
        object $entity
    ) : void;
}
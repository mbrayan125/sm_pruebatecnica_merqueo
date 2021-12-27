<?php

namespace App\ProjectInterfaces;

use Iterator;
use Traversable;

interface PersistenceInterface
{
    public static function retrieveEntities(
        string $classPath, 
        array $parameters = array(),
        array $orderBy = array(),
        int $limit = PHP_INT_MAX,
        int $page = 1
    );


    /**
     * \@pendiente_documentacion_stevin
     *
     * @param object $entity
     *
     * @return void
     * @throws [type] Cuando ocurra alguna excepción en el procesamiento
     *
     * @author Stiven Mamián <brayan.mamian@makrosoft.co>
     */
    public static function saveEntity(
        object $entity
    ) : void;
}
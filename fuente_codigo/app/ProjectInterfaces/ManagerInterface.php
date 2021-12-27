<?php

namespace App\ProjectInterfaces;

interface ManagerInterface
{
    /**
     * \@pendiente_documentacion_stevin
     *
     * @param string $classPath
     * @param array $dataCreation
     *
     * @return object
     * @throws [type] Cuando ocurra alguna excepción en el procesamiento
     *
     * @author Stiven Mamián <brayan.mamian@makrosoft.co>
     */
    public static function createEntity(
        array $data
    ) : object;
}
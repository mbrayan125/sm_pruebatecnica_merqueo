<?php

namespace App\ProjectElements\Managers;

use App\ProjectInterfaces\ManagerInterface;
use Exception;

abstract class ClassManager implements ManagerInterface
{
    /**
     * \@pendiente_documentacion_stevin
     *
     * @param string $classPath
     * @param array $data
     * @param array $mandatoryData
     *
     * @return void
     * @throws [type] Cuando ocurra alguna excepción en el procesamiento
     *
     * @author Stiven Mamián <brayan.mamian@makrosoft.co>
     */
    protected static function checkDataItems(
        string $classPath,
        array $data,
        array $mandatoryData
    ) : void {

        foreach ($mandatoryData as $mandatoryData) {
            if (!array_key_exists($mandatoryData, $data)) {
                throw new Exception(sprintf(
                    "New entity %s create failed, mandatory parameter %s not found", 
                    $classPath,
                    $mandatoryData
                ));
            }
        }
    }
}
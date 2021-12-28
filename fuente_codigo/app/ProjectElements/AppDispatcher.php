<?php

namespace App\ProjectElements;

use App\ProjectElements\Persistence\EloquentPersistence;
use App\ProjectElements\Persistence\MemoryPersistence;
use App\ProjectHelpers\Files\LinuxFilesHelper;
use App\ProjectInterfaces\Helpers\FilesHelperInterface;
use App\ProjectInterfaces\PersistenceInterface;
use Exception;

class AppDispatcher 
{
    /**
     * @var array
     */
    private static $avaiableContext = [
        "prod",
        "test"
    ];
    
    /**
     * @var \App\ProjectInterfaces\PersistenceInterface
     */
    private static $persistenceManager;

    /**
     * @var \App\ProjectInterfaces\Helpers\FilesHelperInterface
     */
    private static $filesHelper;

    /**
     * \@pendiente_documentacion_stevin
     *
     * @return PersistenceInterface
     * @throws [type] Cuando ocurra alguna excepción en el procesamiento
     *
     * @author Stiven Mamián <brayan.mamian@makrosoft.co>
     */
    public static function getPersistenceManager() : PersistenceInterface
    {
        return self::$persistenceManager;
    }

    /**
     * \@pendiente_documentacion_stevin
     *
     * @return FilesHelperInterface
     * @throws [type] Cuando ocurra alguna excepción en el procesamiento
     *
     * @author Stiven Mamián <brayan.mamian@makrosoft.co>
     */
    public static function getFilesHelper() : FilesHelperInterface
    {
        return self::$filesHelper;
    }

    /**
     * \@pendiente_documentacion_stevin
     *
     * @param string $context
     *
     * @return void
     * @throws [type] Cuando ocurra alguna excepción en el procesamiento
     *
     * @author Stiven Mamián <brayan.mamian@makrosoft.co>
     */
    public static function setUpDispatcher(string $context = "prod")
    {
        if (!in_array($context, self::$avaiableContext)) {
            throw new Exception("Context $context not defined");
        }

        if ($context == "prod") {
            self::$persistenceManager = new EloquentPersistence();
            self::$filesHelper = new LinuxFilesHelper();
        }

        if ($context == "test") {
            self::$persistenceManager = new MemoryPersistence();
            self::$filesHelper = new LinuxFilesHelper();
        }
    }
}
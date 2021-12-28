<?php

namespace App\ProjectElements\Repository;

use App\ProjectElements\AppDispatcher;

abstract class ModelRepository
{
    private $persistenceManager;

    public function __construct()
    {
        $this->persistenceManager = AppDispatcher::getPersistenceManager();
    }

    protected function getPersistenceManager()
    {
        return $this->persistenceManager;
    }
}
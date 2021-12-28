<?php

namespace App\Football\Managers;

use App\Football\Models\PhaseGroup;
use App\ProjectElements\Managers\ClassManager;

class PhaseGroupManager extends ClassManager
{
    private static $mandatoryData = array(
        "name",
        "phase"
    );

    public static function createEntity(array $data): object
    {
        self::checkDataItems(
            PhaseGroup::class, 
            $data, 
            self::$mandatoryData
        );

        $data = (object) $data;
        $newPhaseGroupObject = new PhaseGroup();
        $newPhaseGroupObject->setName($data->name);
        $newPhaseGroupObject->setPhase($data->phase);

        return $newPhaseGroupObject;
    }
}
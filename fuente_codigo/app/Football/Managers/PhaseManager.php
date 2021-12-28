<?php

namespace App\Football\Managers;

use App\Football\Models\Phase;
use App\ProjectElements\Managers\ClassManager;

class PhaseManager extends ClassManager
{
    private static $mandatoryData = array(
        "orderPhase",
        "name",
        "championship"
    );

    public static function createEntity(array $data): object
    {
        self::checkDataItems(
            Phase::class, 
            $data, 
            self::$mandatoryData
        );

        $data = (object) $data;
        $newPhaseObject = new Phase();
        $newPhaseObject->setOrderPhase($data->orderPhase);
        $newPhaseObject->setName($data->name);
        $newPhaseObject->setChampionship($data->championship);

        return $newPhaseObject;
    }
}
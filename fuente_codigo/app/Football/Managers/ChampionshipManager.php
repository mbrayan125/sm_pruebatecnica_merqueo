<?php

namespace App\Football\Managers;

use App\Football\Models\Championship;
use App\Football\Models\Team;
use App\ProjectElements\AppDispatcher;
use App\ProjectElements\Managers\ClassManager;
use Exception;

class ChampionshipManager extends ClassManager
{
    private static $mandatoryData = array(
        "name",
        "championshipYear",
        "championshipMonth"
    );

    public static function createEntity(array $data): object
    {
        self::checkDataItems(
            Championship::class, 
            $data, 
            self::$mandatoryData
        );

        $data = (object) $data;
        $newChampionshipObject = new Championship();
        $newChampionshipObject->setName($data->name);
        $newChampionshipObject->setChampionshipYear($data->championshipYear);
        $newChampionshipObject->setChampionshipMonth($data->championshipMonth);

        return $newChampionshipObject;
    }
}
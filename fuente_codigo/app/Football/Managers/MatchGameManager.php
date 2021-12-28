<?php

namespace App\Football\Managers;

use App\Football\Models\MatchGame;
use App\Football\Models\Phase;
use App\ProjectElements\Managers\ClassManager;

class MatchGameManager extends ClassManager
{
    private static $mandatoryData = array(
        "matchNumber",
        "localTeam",
        "visitorTeam",
        "championship",
        "phase"
    );

    public static function createEntity(array $data): object
    {
        self::checkDataItems(
            MatchGame::class, 
            $data, 
            self::$mandatoryData
        );

        $data = (object) $data;
        $newMatchGameObject = new MatchGame();
        $newMatchGameObject->setMatchNumber($data->matchNumber);
        $newMatchGameObject->setLocalTeam($data->localTeam);
        $newMatchGameObject->setVisitorTeam($data->visitorTeam);
        $newMatchGameObject->setChampionship($data->championship);
        $newMatchGameObject->setPhase($data->phase);
        $newMatchGameObject->setStadium(self::getRandomStadium());

        return $newMatchGameObject;
    }

    private static function getRandomStadium()
    {
        return "Allianz Arena";
    }
}
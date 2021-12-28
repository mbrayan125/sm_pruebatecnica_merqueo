<?php

namespace App\Football\Managers;

use App\Football\Models\MatchGame;
use App\ProjectElements\Managers\ClassManager;

class MatchGameManager extends ClassManager
{
    private static $mandatoryData = array(
        "matchNumber",
        "localTeam",
        "visitorTeam",
        "championship",
        "phaseGroup"
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
        $newMatchGameObject->setLocalGoals(0);
        $newMatchGameObject->setLocalYellowCards(0);
        $newMatchGameObject->setLocalRedCards(0);
        $newMatchGameObject->setVisitorGoals(0);
        $newMatchGameObject->setVisitorYellowCards(0);
        $newMatchGameObject->setVisitorRedCards(0);
        $newMatchGameObject->setLocalTeam($data->localTeam);
        $newMatchGameObject->setVisitorTeam($data->visitorTeam);
        $newMatchGameObject->setChampionship($data->championship);
        $newMatchGameObject->setPhaseGroup($data->phaseGroup);
        $newMatchGameObject->setStadium(self::getRandomStadium());

        return $newMatchGameObject;
    }

    private static function getRandomStadium()
    {
        return "Allianz Arena";
    }
}
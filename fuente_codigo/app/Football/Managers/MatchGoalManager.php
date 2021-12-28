<?php

namespace App\Football\Managers;

use App\Football\Models\MatchGoal;
use App\ProjectElements\Managers\ClassManager;

class MatchGoalManager extends ClassManager
{
    private static $mandatoryData = array(
        "player",
        "half",
        "minute",
        "matchGame"
    );

    public static function createEntity(array $data): object
    {
        self::checkDataItems(
            MatchGoal::class, 
            $data, 
            self::$mandatoryData
        );

        $data = (object) $data;
        $newMatchGoal = new MatchGoal();
        foreach ($data as $property => $value) {
            $method = "set" . lcfirst($property);
            $newMatchGoal->__call($method, [$value]);
        }

        return $newMatchGoal;
    }
}
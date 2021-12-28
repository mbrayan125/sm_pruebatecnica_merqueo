<?php

namespace App\Football\Managers;

use App\Football\Models\MatchCard;
use App\Football\Models\MatchGoal;
use App\Football\Models\Phase;
use App\ProjectElements\Managers\ClassManager;

class MatchCardManager extends ClassManager
{
    private static $mandatoryData = array(
        "type",
        "player",
        "half",
        "minute",
        "matchGame"
    );

    public static function createEntity(array $data): object
    {
        self::checkDataItems(
            MatchCard::class, 
            $data, 
            self::$mandatoryData
        );

        $data = (object) $data;
        $newMatchGoal = new MatchCard();
        foreach ($data as $property => $value) {
            $method = "set" . lcfirst($property);
            $newMatchGoal->__call($method, [$value]);
        }

        return $newMatchGoal;
    }
}
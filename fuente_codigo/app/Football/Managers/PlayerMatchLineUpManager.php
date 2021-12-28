<?php

namespace App\Football\Managers;

use App\Football\Models\Phase;
use App\Football\Models\PlayerMatchLineUp;
use App\ProjectElements\Managers\ClassManager;

class PhaseManager extends ClassManager
{
    private static $mandatoryData = array(
        "playerBand",
        "formationType",
        "matchGame",
        "player"
    );

    public static function createEntity(array $data): object
    {
        self::checkDataItems(
            PlayerMatchLineUp::class, 
            $data, 
            self::$mandatoryData
        );

        $data = (object) $data;
        $newPlayerMatchLineUpObject = new PlayerMatchLineUp();
        $newPlayerMatchLineUpObject->setPlayerBand($data->playerBand);
        $newPlayerMatchLineUpObject->setFormationType($data->formationType);
        $newPlayerMatchLineUpObject->setMatchGame($data->matchGame);
        $newPlayerMatchLineUpObject->setPlayer($data->player);

        return $newPlayerMatchLineUpObject;
    }
}
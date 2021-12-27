<?php

namespace App\Football\Managers;

use App\Football\Models\Player;
use App\Football\Models\Team;
use App\ProjectElements\AppDispatcher;
use App\ProjectElements\Managers\ClassManager;

class PlayerManager extends ClassManager
{
    private static $classPath = "App\Football\Models\Player";
    private static $mandatoryData = [
        "name",
        "dorsalName",
        "dorsalNumber",
        "birthYear",
        "birthMonth",
        "gamePosition",
        "photoPath",
        "team_id"
    ];
    private static $gamePositions = [
        "goalkeeper",
        "defender",
        "midfielder",
        "foward"
    ];

    public static function createEntity(
        array $data
    ): object {

        self::checkDataItems(self::$classPath, $data, self::$mandatoryData);
        $data = (object) $data;
        $newPlayerObject = new Player();
        $newPlayerObject->setName($data->name);
        $newPlayerObject->setDorsalName($data->dorsalName);
        $newPlayerObject->setDorsalNumber($data->dorsalNumber);
        $newPlayerObject->setBirthYear($data->birthYear);
        $newPlayerObject->setBirthMonth($data->birthMonth);
        $newPlayerObject->setGamePosition($data->gamePosition);
        $newPlayerObject->setPhotoPath($data->photoPath);
        $newPlayerObject->setTeam_id($data->team_id);
        return $newPlayerObject;
    }

    public static function moveOnFlagPath(
        string $originalPath,
        string $teamCountry,
        string $playerName
    ): string {

        $filesHelper = AppDispatcher::getFilesHelper();
        $finalRoute = $filesHelper::createRoute(
            "Football",
            "photos",
            str_replace(" ", "_", strtolower($teamCountry)),
            str_replace(" ", "_", strtolower($playerName))
        );
        $finalAbsoluteRoute = $filesHelper::createRoute(
            public_path(),
            $finalRoute
        );
        $filesHelper::copyFile($originalPath, $finalAbsoluteRoute);

        return $finalRoute;
    }
}
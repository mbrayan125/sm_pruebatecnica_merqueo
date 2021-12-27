<?php

namespace App\Football\Managers;

use App\Football\Models\Team;
use App\ProjectElements\AppDispatcher;
use App\ProjectElements\Managers\ClassManager;

class TeamManager extends ClassManager
{
    private static $classPath = "App\Football\Models\Team";
    private static $mandatoryData = [
        "country",
        "flag",
        "rank",
        "nationality"
    ];

    public static function createEntity(
        array $data
    ): object {

        self::checkDataItems(
            self::$classPath, 
            $data, 
            self::$mandatoryData
        );
        $data = (object) $data;
        $newTeamObject = new Team();
        $newTeamObject->setCountry($data->country);
        $newTeamObject->setFlag($data->flag);
        $newTeamObject->setRank($data->rank);
        $newTeamObject->setNationality($data->nationality);
        return $newTeamObject;
    }



    public static function moveOnFlagPath(
        string $originalPath,
        string $countryName
    ): string {

        $filesHelper = AppDispatcher::getFilesHelper();
        $finalRoute = $filesHelper::createRoute(
            "Football",
            "flags",
            str_replace(" ", "_", strtolower($countryName))
        );
        $finalAbsoluteRoute = $filesHelper::createRoute(
            public_path(),
            $finalRoute
        );
        $filesHelper::copyFile($originalPath, $finalAbsoluteRoute);

        return $finalRoute;
    }
}
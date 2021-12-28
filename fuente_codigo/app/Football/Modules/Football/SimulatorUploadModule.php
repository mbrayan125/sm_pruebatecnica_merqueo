<?php

namespace App\Football\Modules\Football;

use App\Football\Managers\PlayerManager;
use App\Football\Managers\TeamManager;
use App\Football\Models\Player;
use App\Football\Models\Team;
use App\ProjectElements\AppDispatcher;
use Exception;

class SimulatorUploadModule
{
    public static function loadTeamsFromCsv(
        string $csvTeamsPath,
        string $flagsPath
    ) {

        $persistenceManager = AppDispatcher::getPersistenceManager();
        $filesHelper = AppDispatcher::getFilesHelper();
        $filesHelper::validateFileAccess($csvTeamsPath, $flagsPath);

        $pathFlagsFolder = $filesHelper::createRoute(
            "tmp",
            uniqid("flags_")
        );

        $handleFile = fopen($csvTeamsPath, "r");
        if ($handleFile === FALSE) {
            throw new Exception("Unable to open csv file $csvTeamsPath");
        }

        while(($dataCsv = fgetcsv($handleFile)) !== FALSE) 
        {
            try {
                $country = $dataCsv[0];
                $flag = $dataCsv[1];
                $rank = $dataCsv[2];
                $nationality = $dataCsv[3];
                
                $filesHelper::decompressZipFile(
                    $flagsPath, 
                    $pathFlagsFolder,
                    $flag
                );
                $pathToFlag = $filesHelper::createRoute(
                    $pathFlagsFolder,
                    $flag
                );

                $newTeam = TeamManager::createEntity([
                    "country"       => $country,
                    "flag"          => $pathToFlag,
                    "rank"          => $rank,
                    "nationality"   => $nationality
                ]);
                $persistenceManager::saveEntity($newTeam);

            } catch (Exception $ex) {
                continue;
            }
        }
    }

    public static function loadPlayersFromCsv(
        string $csvPlayersPath,
        string $photosPath
    ) {

        $persistenceManager = AppDispatcher::getPersistenceManager();
        $filesHelper = AppDispatcher::getFilesHelper();
        $filesHelper::validateFileAccess($csvPlayersPath, $photosPath);

        $pathPhotosFolder = $filesHelper::createRoute(
            "tmp",
            uniqid("photos_")
        );

        $handleFile = fopen($csvPlayersPath, "r");
        if ($handleFile === FALSE) {
            throw new Exception("Unable to open csv file $csvPlayersPath");
        }

        while(($dataCsv = fgetcsv($handleFile)) !== FALSE) 
        {
            try {

                $team = $dataCsv[0];
                $name = $dataCsv[1];
                $dorsalName = $dataCsv[2];
                $dorsalNumber = $dataCsv[3];
                $birthYear = $dataCsv[4];
                $birthMonth = $dataCsv[5];
                $gamePosition = $dataCsv[6];
                $photo = $dataCsv[7];

                $targetTeams = $persistenceManager::findBy(
                    Team::class,
                    array(
                        [ "country", "=", $team ]
                    )
                );

                if (sizeof($targetTeams) != 1) {
                    throw new Exception("Team $team not found or found multiple teams");
                }

                $targetTeam = $targetTeams[0];
                $team = $targetTeam->getId();
                
                $filesHelper::decompressZipFile(
                    $photosPath, 
                    $pathPhotosFolder,
                    $photo
                );
                $pathToPhoto = $filesHelper::createRoute(
                    $pathPhotosFolder,
                    $photo
                );

                $newPlayer = PlayerManager::createEntity([
                    "name"          => $name,
                    "dorsalName"    => $dorsalName,
                    "dorsalNumber"  => $dorsalNumber,
                    "birthYear"     => $birthYear,
                    "birthMonth"    => $birthMonth,
                    "gamePosition"  => $gamePosition,
                    "photoPath"     => $pathToPhoto,
                    "team"          => $team
                ]);
                $persistenceManager::saveEntity($newPlayer);


            } catch (Exception $ex) {
                continue;
            }
        }
    }
}

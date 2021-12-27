<?php

namespace App\Football\Modules;

use App\Football\Managers\PlayerManager;
use App\Football\Managers\TeamManager;
use App\Football\Models\Player;
use App\Football\Models\Team;
use App\ProjectElements\AppDispatcher;
use Exception;

class FootballSimulatorUploadModule
{
    public static function loadTeamsFromCsv(
        string $csvTeamsPath,
        string $flagsPath
    ) {
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

                self::addNewTeam([
                    "country"       => $country,
                    "flag"          => $pathToFlag,
                    "rank"          => $rank,
                    "nationality"   => $nationality
                ]);
            } catch (Exception $ex) {
                continue;
            }
        }
    }

    public static function addNewTeam(array $data)
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();

        $loadedTeam = TeamManager::createEntity($data);
        $currentTeam = $persistenceManager::retrieveEntities(
            Team::class,
            array(
                [ "country", "=", $loadedTeam->getCountry() ]
            )
        );
        $teamsFound = sizeof($currentTeam);
        if ($teamsFound > 0) {
            throw new Exception(sprintf(
                "Team %s already exists", 
                $loadedTeam->getCountry()
            ));
        }

        $finalFlagPath = TeamManager::moveOnFlagPath(
            $loadedTeam->getFlag(), 
            $loadedTeam->getCountry()
        );
        $loadedTeam->setFlag($finalFlagPath);
        $persistenceManager::saveEntity($loadedTeam);
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

                $targetTeams = $persistenceManager::retrieveEntities(
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

                self::addNewPlayer([
                    "name"          => $name,
                    "dorsalName"    => $dorsalName,
                    "dorsalNumber"  => $dorsalNumber,
                    "birthYear"     => $birthYear,
                    "birthMonth"    => $birthMonth,
                    "gamePosition"  => $gamePosition,
                    "photoPath"     => $pathToPhoto,
                    "team_id"       => $team
                ]);
            } catch (Exception $ex) {
                
                continue;
            }
        }
    }

    public static function addNewPlayer(array $data) 
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();

        $loadedPlayer = PlayerManager::createEntity($data);
        $currentPlayer = $persistenceManager::retrieveEntities(
            Player::class,
            array(
                [ "team_id", "=", $loadedPlayer->getTeam_id() ],
                [ "name", "=", $loadedPlayer->getName() ],
                [ "dorsalName", "=", $loadedPlayer->getDorsalName() ]
            )
        );
        $targetTeams = $persistenceManager::retrieveEntities(
            Team::class,
            array(
                [ "id", "=", $loadedPlayer->getTeam_id()]
            )
        );
        if (sizeof($targetTeams) != 1) {
            throw new Exception(sprintf(
                "Team %d not found or found multiple teams",
                $loadedPlayer->getTeam_id()
            ));
        }

        $targetTeam = $targetTeams[0];

        $playersFound = sizeof($currentPlayer);
        if ($playersFound > 0) {
            throw new Exception(sprintf(
                "Player %s from %s already exists", 
                $loadedPlayer->getName(),
                $targetTeam->getCountry()
            ));
        }

        $finalPhotoPath = PlayerManager::moveOnFlagPath(
            $loadedPlayer->getPhotoPath(), 
            $targetTeam->getCountry(),
            $loadedPlayer->getName()
        );
        $loadedPlayer->setPhotoPath($finalPhotoPath);
        $persistenceManager::saveEntity($loadedPlayer);
    }
}

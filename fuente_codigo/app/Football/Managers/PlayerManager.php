<?php

namespace App\Football\Managers;

use App\Football\Models\Player;
use App\Football\Models\Team;
use App\ProjectElements\AppDispatcher;
use App\ProjectElements\Managers\ClassManager;
use Exception;

class PlayerManager extends ClassManager
{
    private static $mandatoryData = [
        "name",
        "dorsalName",
        "dorsalNumber",
        "birthYear",
        "birthMonth",
        "gamePosition",
        "photoPath",
        "team"
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

        $persistenceManager = AppDispatcher::getPersistenceManager();
        
        self::checkDataItems(
            Player::class, 
            $data, 
            self::$mandatoryData
        );

        $data = (object) $data;
        $newPlayerObject = new Player();
        $newPlayerObject->setName($data->name);
        $newPlayerObject->setDorsalName($data->dorsalName);
        $newPlayerObject->setDorsalNumber($data->dorsalNumber);
        $newPlayerObject->setBirthYear($data->birthYear);
        $newPlayerObject->setBirthMonth($data->birthMonth);
        $newPlayerObject->setGamePosition($data->gamePosition);
        $newPlayerObject->setPhotoPath($data->photoPath);
        $newPlayerObject->setTeam($data->team);

        $currentPlayer = $persistenceManager::findBy(
            Player::class,
            array(
                [ "team_id", "=", $newPlayerObject->getTeam()->getId() ],
                [ "dorsalName", "=", $newPlayerObject->getDorsalName() ]
            )
        );

        $playersFound = sizeof($currentPlayer);
        if ($playersFound > 0) {
            throw new Exception(sprintf(
                "Player %s from %s already exists", 
                $newPlayerObject->getName(),
                $newPlayerObject->getTeam()->getCountry()
            ));
        }
        
        return $newPlayerObject;
    }

    private static function moveOnPhoto(
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
<?php

namespace App\Football\Modules\Implementation;

use App\Football\Managers\ChampionshipManager;
use App\Football\Managers\PhaseManager;
use App\Football\Models\Championship;
use App\ProjectElements\AppDispatcher;
use App\ProjectInterfaces\FootballSimulator\ChampionshipMakerInterface;
use Exception;

class WorldCupChampionshipMaker implements ChampionshipMakerInterface
{
    private const AMOUNT_OF_TEAMS = 32;

    public static function generateChampionship(string $name, int $year, int $month): Championship
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();
        $worldCupChampionship = ChampionshipManager::createEntity([
            "name" => "World Cup $name",
            "championshipYear" => $year,
            "championshipMonth" => $month 
        ]);
        $persistenceManager::saveEntity($worldCupChampionship);
        $persistenceManager::refreshEntity($worldCupChampionship);
        return $worldCupChampionship;
    }

    public static function generateChampionshipPhases(Championship $championship): void
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();

        $groupsPhase = PhaseManager::createEntity([
            "orderPhase" => 1,
            "name" => "Groups stage",
            "championship" => $championship
        ]);
        $persistenceManager::saveEntity($groupsPhase);

        $roundOf16 = PhaseManager::createEntity([
            "orderPhase" => 2,
            "name" => "Round of 16",
            "championship" => $championship
        ]);
        $persistenceManager::saveEntity($roundOf16);

        $quarterFinals = PhaseManager::createEntity([
            "orderPhase" => 3,
            "name" => "Quarter finals",
            "championship" => $championship
        ]);
        $persistenceManager::saveEntity($quarterFinals);

        $semifinal = PhaseManager::createEntity([
            "orderPhase" => 4,
            "name" => "Semifinal",
            "championship" => $championship
        ]);
        $persistenceManager::saveEntity($semifinal);

        $final = PhaseManager::createEntity([
            "orderPhase" => 5,
            "name" => "Final",
            "championship" => $championship
        ]);
        $persistenceManager::saveEntity($final);


        $persistenceManager::refreshEntity($championship);
    }

    public static function getAmountInitialTeams(): int
    {
        return self::AMOUNT_OF_TEAMS;
    }

    public static function executePhases(Championship $championship): void
    {
        
    }


}
<?php

namespace App\Http\Controllers\Simulator;

use App\Football\Managers\ChampionshipManager;
use App\Football\Managers\MatchGameManager;
use App\Football\Managers\PhaseGroupManager;
use App\Football\Managers\PhaseManager;
use App\Football\Managers\TeamManager;
use App\Football\Managers\TeamPhaseGroupManager;
use App\Football\Models\Championship;
use App\Football\Models\Phase;
use App\Football\Models\Player;
use App\Football\Models\Team;
use App\Football\Modules\Football\ChampionshipSimulator;
use App\Football\Modules\Football\SimulatorUploadModule;
use App\Football\Modules\Implementation\SimpleMatchGameSimulator;
use App\Http\Controllers\Controller;
use App\ProjectElements\AppDispatcher;
use App\ProjectHelpers\Files\ClassHelper;
use ReflectionClass;

class FootballController extends Controller
{
    public function loadTeamsFromFile()
    {
        $pathCsvTeams = "/Aplicaciones/archivos/Documentos prescindibles/Test/teams.csv";
        $pathZipFlags = "/Aplicaciones/archivos/Documentos prescindibles/Test/flags.zip";

        SimulatorUploadModule::loadTeamsFromCsv(
            $pathCsvTeams,
            $pathZipFlags
        );
    }

    public function loadPlayersFromFile()
    {
        $pathCsvPlayers = "/Aplicaciones/archivos/Documentos prescindibles/Test/players.csv";
        $pathZipPhotos = "/Aplicaciones/archivos/Documentos prescindibles/Test/photos.zip";

        SimulatorUploadModule::loadPlayersFromCsv(
            $pathCsvPlayers,
            $pathZipPhotos
        );
    }

    public function startSimulation()
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();
        $team1 = $persistenceManager::findOneBy(
            Team::class,
            [
                [ "id", "=", 2]
            ]
        );
        $team2 = $persistenceManager::findOneBy(
            Team::class,
            [
                [ "id", "=", 25]
            ]
        );
        $matchSimulator = new SimpleMatchGameSimulator();
        $result = (array) $matchSimulator::simulateMatch($team1, $team2);

        echo "<pre>";
        print_r($result);
        echo "</pre>";

        return;
       $simulator = new ChampionshipSimulator();
       $simulator->startSimulation(
           "Qatar",
           2022,
           8
       );
    }

    public function utileCheck()
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();

        $print = array();
        $championship = ChampionshipManager::createEntity([
            "name" => "Qatar World Cup 2018",
            "championshipYear" => 2018,
            "championshipMonth" => 6
        ]);

        //$print[] = $championship->getName();
        //$persistenceManager->saveEntity($championship);

        $phase = PhaseManager::createEntity([
            "orderPhase" => 1,
            "name" => "Groups stage",
            "championship" => 1
        ]);

        //$print[] = $phase->getName();
        //$persistenceManager->saveEntity($phase);

        $phaseGroup = PhaseGroupManager::createEntity([
            "name" => "Final Octogonal",
            "phase" => 1
        ]);

        //$print[] = $phaseGroup->getName();
        //$persistenceManager->saveEntity($phaseGroup);

        
        $teamSelected = $persistenceManager::findOneBy(
            Team::class,
            array(
                [ "country", "=", "Portugal" ]
            )
        );
        $teamPhaseGroup = TeamPhaseGroupManager::createEntity([
            "team" => $teamSelected,
            "phaseGroup" => 1
        ]);

        //$print[] = "Phase group created";
        //$print[] = "";

        
        //$persistenceManager->saveEntity($teamPhaseGroup);

        $championship = $persistenceManager::findOneBy(
            Championship::class
        );
        $print[] = $championship->getName();

        $matchGames = array();
        $phases = $championship->getPhases();
        foreach ($phases as $phase) {
            $print[] = $phase->getName();
            $phaseGroups = $phase->getPhaseGroups();
            $fecha = 1;
            foreach ($phaseGroups as $phaseGroup) {
                $print[] = $phaseGroup->getName();
                $teamsPhaseGroup = $phaseGroup->getTeamsPhaseGroups();
                foreach($teamsPhaseGroup as $localTeamPhaseGroup) {
                    $fecha = 1;
                    $localTeam = $localTeamPhaseGroup->getTeam();

                    foreach($teamsPhaseGroup as $visitorTeamPhaseGroup) {
                        $visitorTeam = $visitorTeamPhaseGroup->getTeam();
                        if ($localTeam->getId() == $visitorTeam->getId()) {
                            continue;
                        }
                        $match = MatchGameManager::createEntity([
                            "matchNumber" => 0,
                            "championship" => $championship,
                            "localTeam" => $localTeam,
                            "visitorTeam" => $visitorTeam,
                            "phase" => $phase
                        ]);
                        $matchGames[$fecha][] = $match;
                    }
                }
                $fecha ++;
            }
        }

        foreach ($matchGames as $fecha => $matches) {
            $print[] = "--";
            $print[] = "Fecha $fecha";
            $print[] = "--";
            foreach($matches as $match) {
                $localTeam = $match->getLocalTeam();
                $visitorTeam = $match->getVisitorTeam();
                $print[] = "Match " . $localTeam->getCountry() . " vs " .$visitorTeam->getCountry();
            }
        }


        print(" > " . implode("<br> > ", $print));
        
        /*
        $persistenceManager::saveEntity($championship);
        */

    }
}

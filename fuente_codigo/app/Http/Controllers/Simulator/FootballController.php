<?php

namespace App\Http\Controllers\Simulator;

use App\Football\Models\Championship;
use App\Football\Modules\Football\ChampionshipSimulator;
use App\Football\Modules\Football\SimulatorUploadModule;
use App\Http\Controllers\Controller;
use App\ProjectElements\AppDispatcher;

class FootballController extends Controller
{
    public function loadTeamsFromFile()
    {
        $pathCsvTeams = "/Aplicaciones/uploads/teams.csv";
        $pathZipFlags = "/Aplicaciones/uploads/flags.zip";

        SimulatorUploadModule::loadTeamsFromCsv(
            $pathCsvTeams,
            $pathZipFlags
        );
    }

    public function loadPlayersFromFile()
    {
        $pathCsvPlayers = "/Aplicaciones/uploads/players.csv";
        $pathZipPhotos = "/Aplicaciones/uploads/photos.zip";

        SimulatorUploadModule::loadPlayersFromCsv(
            $pathCsvPlayers,
            $pathZipPhotos
        );
    }

    public function listSimulations()
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();
        $championships = $persistenceManager::findBy(
            Championship::class
        );

        $return = array();
        foreach ($championships as $championship) {
            $return[] = " > id: " . $championship->getId() . " " .$championship->getName();
        }
        $this->addDefaultHelp($return);
        return implode("<br>", $return);
    }

    public function viewSimulation($idChampionship)
    {
        $report = array();
        $persistenceManager = AppDispatcher::getPersistenceManager();
        $championship = $persistenceManager::findOneBy(
            Championship::class,
            [
                [ "id", "=", $idChampionship]
            ]
        );
        if ($championship) {
            $simulator = new ChampionshipSimulator();
            $report = $simulator->makeReport($championship);
        }
        $this->addDefaultHelp($report);
        return implode("<br>", $report);
    }

    public function newSimulation()
    {
        $simulationId = uniqid("simulation_");
        $simulator = new ChampionshipSimulator();
        $report = $simulator->startSimulation(
            $simulationId,
            2022,
            8
        );

        $this->addDefaultHelp($report);
        return implode("<br>", $report);
    }

    public function simulationHelp()
    {
        $return = array();
        $return[] = "";
        $return[] = "Opciones";
        $return[] = "";
        $return[] = (" > Para visualizar los resultados de una simulaci??n => /simulation/view/{id}");
        $return[] = (" > Para generar una simulaci??n desde cero => /simulation/new");
        $return[] = (" > Para listar las simulaciones generadas => /simulation/list");
        $return[] = (" > Para cargar equipos desde un archivo csv => /simulation/load/help");
        $this->addDefaultHelp($return);
        return implode("<br>", $return);
    }

    public function simulationLoadHelp()
    {
        $return = array();
        $return[] = "";
        $return[] = "Opciones";
        $return[] = "";
        $return[] = (" > Para cargar equipos desde un csv => /simulation/load/teams ");
        $return[] = (" * Esta acci??n cargar?? por defecto la informaci??n contenida en los archivos ");
        $return[] = (" * teams.csv (con la informaci??n de los equipos) y flags.zip (con las banderas de");
        $return[] = (" * cada equipo ubicados en la direcci??n relativa <path repository>/archivos/");
        $return[] = (" * artefactos/uploads, si se desea usar otro archivo csv entonces reeemplazar");
        $return[] = (" * cada uno de estos con informaci??n v??lida");
        $return[] = "";
        $return[] = (" > Para cargar jugadores desde un csv => /simulation/load/players ");
        $return[] = (" * Esta acci??n cargar?? por defecto la informaci??n contenida en los archivos ");
        $return[] = (" * players.csv (con la informaci??n de los equipos) y photos.zip (con las fotos de");
        $return[] = (" * cada jugador ubicados en la direcci??n relativa <path repository>/archivos/");
        $return[] = (" * artefactos/uploads, si se desea usar otro archivo csv entonces reeemplazar");
        $return[] = (" * cada uno de estos con informaci??n v??lida");
        $this->addDefaultHelp($return);
        return implode("<br>", $return);
    }

    private function addDefaultHelp(array &$array) 
    {
        $array[] = "";
        $array[] = "";
        $array[] = (" > Ver todos los comandos => /simulation/help");

    }
}

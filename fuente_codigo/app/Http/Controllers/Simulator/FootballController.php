<?php

namespace App\Http\Controllers\Simulator;

use App\Football\Managers\PlayerManager;
use App\Football\Managers\TeamManager;
use App\Football\Models\Team;
use App\Football\Modules\FootballSimulatorUploadModule;
use App\Http\Controllers\Controller;
use App\ProjectElements\AppDispatcher;
use Illuminate\Foundation\Testing\WithFaker;

class FootballController extends Controller
{
    public function loadTeamsFromFile()
    {
        $pathCsvTeams = "/Aplicaciones/archivos/Documentos prescindibles/Test/teams.csv";
        $pathZipFlags = "/Aplicaciones/archivos/Documentos prescindibles/Test/flags.zip";

        FootballSimulatorUploadModule::loadTeamsFromCsv(
            $pathCsvTeams,
            $pathZipFlags
        );
    }

    public function loadPlayersFromFile()
    {
        $pathCsvPlayers = "/Aplicaciones/archivos/Documentos prescindibles/Test/players.csv";
        $pathZipPhotos = "/Aplicaciones/archivos/Documentos prescindibles/Test/photos.zip";

        FootballSimulatorUploadModule::loadPlayersFromCsv(
            $pathCsvPlayers,
            $pathZipPhotos
        );

    }
}

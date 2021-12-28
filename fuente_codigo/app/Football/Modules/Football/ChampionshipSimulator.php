<?php

namespace App\Football\Modules\Football;

use App\Football\Modules\Implementation\BestRankTeamChooser;
use App\Football\Modules\Implementation\WorldCupChampionshipMaker;
use App\ProjectInterfaces\FootballSimulator\ChampionshipMakerInterface;
use App\ProjectInterfaces\FootballSimulator\ChampionshipTeamChooserInterface;

class ChampionshipSimulator
{
    private $championshipMaker;
    private $teamChooser;
    private $championshipSimulated;
    private $selectedTeams;

    public function __construct(
        ?ChampionshipMakerInterface $championshipMaker = null,
        ?ChampionshipTeamChooserInterface $teamChooser = null
    ) {
        if (is_null($championshipMaker)) {
            $championshipMaker = new WorldCupChampionshipMaker();
        }
        if (is_null($teamChooser)) {
            $teamChooser = new BestRankTeamChooser();
        }
        $this->championshipMaker = $championshipMaker;
        $this->teamChooser = $teamChooser;
    }

    public function startSimulation(
        string $name,
        int $year,
        int $month
    ) {
        $this->championshipSimulated = $this->championshipMaker::generateChampionship(
            $name,
            $year,
            $month
        );

        $this->championshipMaker::generateChampionshipPhases(
            $this->championshipSimulated
        );

        $this->selectedTeams = $this->teamChooser::selectChampionshipTeams(
            $this->championshipMaker::getAmountInitialTeams()
        );
    }
}
<?php

namespace App\ProjectInterfaces\FootballSimulator;

use App\Football\Models\Championship;
use App\Football\Models\PhaseGroup;
use App\Football\Models\Team;
use stdClass;

interface ChampionshipMakerInterface
{
    public static function generateChampionship(
        string $name,
        int $year,
        int $month
    ): Championship;

    public static function executeSimulation(
        Championship $championship,
        $teams,
        MatchGameSimulatorInterface $matchSimulator
    ): void;

    public static function generateChampionshipPhases(
        Championship $championship
    ): void;

    public static function getAmountInitialTeams(): int;

    public static function getChampionTeam(Championship $championship): ?Team;

    public static function getStandingPhaseGroup(PhaseGroup $phaseGroup): array;

}
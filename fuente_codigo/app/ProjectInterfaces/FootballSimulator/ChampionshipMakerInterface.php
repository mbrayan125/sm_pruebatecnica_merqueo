<?php

namespace App\ProjectInterfaces\FootballSimulator;

use App\Football\Models\Championship;

interface ChampionshipMakerInterface
{
    public static function generateChampionship(
        string $name,
        int $year,
        int $month
    ): Championship;

    public static function generateChampionshipPhases(
        Championship $championship
    ): void;

    public static function getAmountInitialTeams(): int;

    public static function executePhases(
        Championship $championship
    ): void;

}
<?php

namespace App\ProjectInterfaces\FootballSimulator;

use App\Football\Models\Championship;
use App\Football\Models\MatchGame;
use App\Football\Models\Team;
use stdClass;

interface MatchGameSimulatorInterface
{
    public static function simulateMatch(
        MatchGame $match
    ): stdClass;

    public static function calculateChampionshipStats(
        Team $team, 
        Championship $championship
    ): stdClass;
}
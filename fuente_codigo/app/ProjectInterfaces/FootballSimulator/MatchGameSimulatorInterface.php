<?php

namespace App\ProjectInterfaces\FootballSimulator;

use App\Football\Models\Team;
use stdClass;

interface MatchGameSimulatorInterface
{
    public static function simulateMatch(
        Team $localTeam,
        Team $visitorTeam
    ): stdClass;
}
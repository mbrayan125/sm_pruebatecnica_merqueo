<?php

namespace App\ProjectInterfaces\FootballSimulator;

use App\Football\Models\Championship;

interface ChampionshipTeamChooserInterface
{
    public static function selectChampionshipTeams(int $amountTeams): array;

}
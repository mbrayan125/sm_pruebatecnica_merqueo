<?php

namespace App\Football\Modules\Implementation;

use App\Football\Models\Team;
use App\ProjectElements\AppDispatcher;
use App\ProjectInterfaces\FootballSimulator\ChampionshipTeamChooserInterface;

class BestRankTeamChooser implements ChampionshipTeamChooserInterface
{
    public static function selectChampionshipTeams(int $amountTeams)
    {
        $persistenceManager = AppDispatcher::getPersistenceManager();
        return $persistenceManager::findBy(
            Team::class,
            [],
            [
                [ "rank", "asc" ]
            ],
            $amountTeams
        );
    }
}
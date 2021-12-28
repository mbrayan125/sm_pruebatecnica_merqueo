<?php

namespace App\Football\Repositories;

use App\Football\Models\Championship;
use App\Football\Models\MatchGame;
use App\Football\Models\Team;
use App\ProjectElements\Repository\ModelRepository;
use App\ProjectHelpers\Files\ClassHelper;

class TeamRepository extends ModelRepository
{
    public function getMatchesFromTeamInChampionship(
        Championship $championship,
        Team $team
    ) {

        $localMatches = $this->getMatchesLocalFromTeamInChampionship(
            $championship,
            $team
        );
        $visitorMatches = $this->getMatchesVisitorFromTeamInChampionship(
            $championship,
            $team
        );

        $totalMatches = $localMatches->merge($visitorMatches);
        return $totalMatches;
    }


    public function getMatchesLocalFromTeamInChampionship(
        Championship $championship,
        Team $team
    ) {
        $persistenceManager = $this->getPersistenceManager();
        $championshipInfo = json_decode(ClassHelper::getVarClassComment(
            MatchGame::class,
            "championship",
            "relationship"
        ));
        $localTeamInfo = json_decode(ClassHelper::getVarClassComment(
            MatchGame::class,
            "localTeam",
            "relationship"
        ));

        $localMatches = $persistenceManager::findBy(
            MatchGame::class,
            [
                [ $championshipInfo->mappedBy, "=", $championship->getId() ],
                [ $localTeamInfo->mappedBy, "=", $team->getId() ]
            ]
        );

        return $localMatches;
    }

    public function getMatchesVisitorFromTeamInChampionship(
        Championship $championship,
        Team $team
    ) {
        $persistenceManager = $this->getPersistenceManager();
        $championshipInfo = json_decode(ClassHelper::getVarClassComment(
            MatchGame::class,
            "championship",
            "relationship"
        ));
        $visitorTeamInfo = json_decode(ClassHelper::getVarClassComment(
            MatchGame::class,
            "visitorTeam",
            "relationship"
        ));

        $visitorMatches = $persistenceManager::findBy(
            MatchGame::class,
            [
                [ $championshipInfo->mappedBy, "=", $championship->getId() ],
                [ $visitorTeamInfo->mappedBy, "=", $team->getId() ]
            ]
        );

        return $visitorMatches;
    }
}
<?php

namespace App\Football\Repositories;

use App\Football\Models\Championship;
use App\Football\Models\MatchGame;
use App\Football\Models\PhaseGroup;
use App\Football\Models\Team;
use App\Football\Models\TeamPhaseGroup;
use App\ProjectElements\Repository\ModelRepository;
use App\ProjectHelpers\Files\ClassHelper;

class TeamPhaseGroupRepository extends ModelRepository
{
    public function findTeamPhaseGroup(
        Team $team,
        PhaseGroup $phaseGroup
    ) {
        $persistenceManager = $this->getPersistenceManager();

        $phaseGroupInfo = json_decode(ClassHelper::getVarClassComment(
            TeamPhaseGroup::class,
            "phaseGroup",
            "relationship"
        ));
        $teamInfo = json_decode(ClassHelper::getVarClassComment(
            TeamPhaseGroup::class,
            "team",
            "relationship"
        ));

        return $persistenceManager::findOneBy(
            TeamPhaseGroup::class,
            [
                [ $phaseGroupInfo->mappedBy, "=", $phaseGroup->getId() ],
                [ $teamInfo->mappedBy, "=", $team->getId() ]
            ]
        );
    }
}
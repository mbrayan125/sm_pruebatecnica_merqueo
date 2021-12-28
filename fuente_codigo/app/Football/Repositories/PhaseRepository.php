<?php

namespace App\Football\Repositories;

use App\Football\Models\Championship;
use App\Football\Models\Phase;
use App\ProjectElements\Repository\ModelRepository;
use App\ProjectHelpers\Files\ClassHelper;

class PhaseRepository extends ModelRepository
{
    public function findPhaseOfChampionship(
        Championship $championship,
        int $phaseOrder
    ) {
        $persistenceManager = $this->getPersistenceManager();

        $championshipInfo = json_decode(ClassHelper::getVarClassComment(
            Phase::class,
            "championship",
            "relationship"
        ));

        return $persistenceManager::findOneBy(
            Phase::class,
            [
                [ $championshipInfo->mappedBy, "=", $championship->getId() ],
                [ "orderPhase", "=", $phaseOrder ]
            ]
        );
    }
}
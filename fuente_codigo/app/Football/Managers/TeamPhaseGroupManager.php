<?php

namespace App\Football\Managers;

use App\Football\Models\Phase;
use App\Football\Models\TeamPhaseGroup;
use App\ProjectElements\Managers\ClassManager;

class TeamPhaseGroupManager extends ClassManager
{
    private static $mandatoryData = array(
        "phaseGroup",
        "team"
    );

    public static function createEntity(array $data): object
    {
        self::checkDataItems(
            TeamPhaseGroup::class, 
            $data, 
            self::$mandatoryData
        );

        $data = (object) $data;
        $newTeamPhaseGroupObject = new TeamPhaseGroup();
        $newTeamPhaseGroupObject->setPoints(0);
        $newTeamPhaseGroupObject->setPhaseGroup($data->phaseGroup);
        $newTeamPhaseGroupObject->setTeam($data->team);

        return $newTeamPhaseGroupObject;
    }
}
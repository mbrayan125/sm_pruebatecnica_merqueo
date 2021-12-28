<?php

namespace App\Football\Models;

use App\ProjectElements\Models\AppModel;

class TeamPhaseGroup extends AppModel
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var int
     */
    protected $points;

    /**
     * @var array
     * @relationship { "type": "many_to_one", "targetClass": "\\App\\Football\\Models\\PhaseGroup", "inversedBy": "id", "mappedBy": "phasegroup_id"}
     */
    protected $phaseGroup;

    /**
     * @var array
     * @relationship { "type": "many_to_one", "targetClass": "\\App\\Football\\Models\\Team", "inversedBy": "id", "mappedBy": "team_id"}
     */
    protected $team;
}

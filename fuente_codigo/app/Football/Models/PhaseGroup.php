<?php

namespace App\Football\Models;

use App\ProjectElements\Models\AppModel;

class PhaseGroup extends AppModel
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $name;

    /**
     * @var object
     * @relationship { "type": "many_to_one", "targetClass": "\\App\\Football\\Models\\Phase", "inversedBy": "id", "mappedBy": "phase_id"}
     */
    protected $phase;

    /**
     * @var array
     * @relationship { "type": "one_to_many", "targetClass": "\\App\\Football\\Models\\TeamPhaseGroup", "inversedBy": "phasegroup_id", "mappedBy": "id"}
     */
    protected $teamsPhaseGroups;

    /**
     * @var object
     * @relationship { "type": "one_to_many", "targetClass": "\\App\\Football\\Models\\MatchGame", "inversedBy": "phasegroup_id", "mappedBy": "id"}
     */
    protected $matchGames;
}

<?php

namespace App\Football\Models;

use App\ProjectElements\Models\AppModel;

class Phase extends AppModel
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $orderPhase;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var object
     * @relationship { "type": "many_to_one", "targetClass": "\\App\\Football\\Models\\Championship", "inversedBy": "id", "mappedBy": "championship_id"}
     */
    protected $championship;

    /**
     * @var object
     * @relationship { "type": "one_to_many", "targetClass": "\\App\\Football\\Models\\MatchGame", "inversedBy": "phase_id", "mappedBy": "id"}
     */
    protected $matchGames;

    /**
     * @var object
     * @relationship { "type": "one_to_many", "targetClass": "\\App\\Football\\Models\\PhaseGroup", "inversedBy": "phase_id", "mappedBy": "id"}
     */
    protected $phaseGroups;
}

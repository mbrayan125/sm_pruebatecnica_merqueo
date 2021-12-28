<?php

namespace App\Football\Models;

use App\ProjectElements\Models\AppModel;

class Championship extends AppModel
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
     * @var int
     */
    protected $championshipYear;

    /**
     * @var int
     */
    protected $championshipMonth;

    /**
     * @var object
     * @relationship { "type": "one_to_many", "targetClass": "\\App\\Football\\Models\\Phase", "inversedBy": "championship_id", "mappedBy": "id"}
     */
    protected $phases;

    /**
     * @var object
     * @relationship { "type": "one_to_many", "targetClass": "\\App\\Football\\Models\\MatchGame", "inversedBy": "championship_id", "mappedBy": "id"}
     */
    protected $matchGames;
}

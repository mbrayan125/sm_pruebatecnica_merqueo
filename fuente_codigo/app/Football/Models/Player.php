<?php

namespace App\Football\Models;

use App\ProjectElements\Models\AppModel;

class Player extends AppModel
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
     * @var string
     */
    protected $dorsalName;

    /**
     * @var int
     */
    protected $dorsalNumber;

    /**
     * @var int
     */
    protected $birthYear;

    /**
     * @var int
     */
    protected $birthMonth;

    /**
     * @var string
     */
    protected $gamePosition;

    /**
     * @var string
     */
    protected $photoPath;

    /**
     * @var object
     * @relationship { "type": "many_to_one", "targetClass": "\\App\\Football\\Models\\Team", "inversedBy": "id", "mappedBy": "team_id"}
     */
    protected $team;

    /**
     * @var object
     * @relationship { "type": "one_to_many", "targetClass": "\\App\\Football\\Models\\MatchCard", "inversedBy": "player_id", "mappedBy": "id"}
     */
    protected $cards;

    /**
     * @var object
     * @relationship { "type": "one_to_many", "targetClass": "\\App\\Football\\Models\\MatchGoal", "inversedBy": "player_id", "mappedBy": "id"}
     */
    protected $goals;
}

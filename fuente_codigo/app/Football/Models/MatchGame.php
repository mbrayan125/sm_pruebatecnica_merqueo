<?php

namespace App\Football\Models;

use App\ProjectElements\Models\AppModel;

class MatchGame extends AppModel
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $stadium;

    /**
     * @var int
     */
    protected $matchNumber;

    /**
     * @var int
     */
    protected $localGoals;

    /**
     * @var int
     */
    protected $visitorGoals;

    /**
     * @var int
     */
    protected $localYellowCards;

    /**
     * @var int
     */
    protected $visitorYellowCards;

    /**
     * @var int
     */
    protected $localRedCards;

    /**
     * @var int
     */
    protected $visitorRedCards;
    
    /**
     * @var string
     */
    protected $wayToWin;

    /**
     * @var object
     * @relationship { "type": "many_to_one", "targetClass": "\\App\\Football\\Models\\Team", "inversedBy": "id", "mappedBy": "local_team_id"}
     */
    protected $localTeam;

    /**
     * @var object
     * @relationship { "type": "many_to_one", "targetClass": "\\App\\Football\\Models\\Team", "inversedBy": "id", "mappedBy": "visitor_team_id"}
     */
    protected $visitorTeam;

    /**
     * @var object
     * @relationship { "type": "many_to_one", "targetClass": "\\App\\Football\\Models\\Team", "inversedBy": "id", "mappedBy": "winner_team_id"}
     */
    protected $winnerTeam;


    /**
     * @var object
     * @relationship { "type": "one_to_many", "targetClass": "\\App\\Football\\Models\\PlayerMatchLineUp", "inversedBy": "matchlineup_id", "mappedBy": "id"}
     */
    protected $matchPlayers;

    /**
     * @var object
     * @relationship { "type": "many_to_one", "targetClass": "\\App\\Football\\Models\\Championship", "inversedBy": "id", "mappedBy": "championship_id"}
     */
    protected $championship;

    /**
     * @var object
     * @relationship { "type": "many_to_one", "targetClass": "\\App\\Football\\Models\\PhaseGroup", "inversedBy": "id", "mappedBy": "phasegroup_id"}
     */
    protected $phaseGroup;

    /**
     * @var object
     * @relationship { "type": "one_to_many", "targetClass": "\\App\\Football\\Models\\MatchGoal", "inversedBy": "matchgame_id", "mappedBy": "id"}
     */
    protected $goals;

    /**
     * @var object
     * @relationship { "type": "one_to_many", "targetClass": "\\App\\Football\\Models\\MatchCard", "inversedBy": "matchgame_id", "mappedBy": "id"}
     */
    protected $cards;
}

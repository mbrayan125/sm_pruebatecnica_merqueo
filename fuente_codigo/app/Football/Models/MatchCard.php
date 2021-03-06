<?php

namespace App\Football\Models;

use App\ProjectElements\Models\AppModel;

class MatchCard extends AppModel
{
    /**
     * @var int
     */
    protected $id;
    
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $minute;

    /**
     * @var int
     */
    protected $half;

    /**
     * @var object
     * @relationship { "type": "many_to_one", "targetClass": "\\App\\Football\\Models\\MatchGame", "inversedBy": "id", "mappedBy": "matchgame_id"}
     */
    protected $matchGame;

    /**
     * @var object
     * @relationship { "type": "many_to_one", "targetClass": "\\App\\Football\\Models\\Player", "inversedBy": "id", "mappedBy": "player_id"}
     */
    protected $player;
}

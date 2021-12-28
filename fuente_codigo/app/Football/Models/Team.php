<?php

namespace App\Football\Models;

use App\ProjectElements\Models\AppModel;

class Team extends AppModel
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $flag;

    /**
     * @var int
     */
    protected $rank;

    /**
     * @var string
     */
    protected $nationality;

    /**
     * @var array
     * @relationship { "type": "one_to_many", "targetClass": "\\App\\Football\\Models\\Player", "inversedBy": "team_id", "mappedBy": "id"}
     */
    protected $players;

    /**
     * @var array
     * @relationship { "type": "one_to_many", "targetClass": "\\App\\Football\\Models\\TeamPhaseGroup", "inversedBy": "team_id", "mappedBy": "id"}
     */
    protected $dataPhases;

}

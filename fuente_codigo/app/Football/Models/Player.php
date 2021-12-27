<?php

namespace App\Football\Models;

use App\ProjectElements\Persistence\LaravelModel;

class Player extends LaravelModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $dorsalName;

    /**
     * @var int
     */
    private $dorsalNumber;

    /**
     * @var int
     */
    private $birthYear;

    /**
     * @var int
     */
    private $birthMonth;

    /**
     * @var string
     */
    private $gamePosition;

    /**
     * @var string
     */
    private $photoPath;

    /**
     * @var int
     */
    private $team_id;
}

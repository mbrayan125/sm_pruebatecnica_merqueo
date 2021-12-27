<?php

namespace App\Football\Models;

use App\ProjectElements\Persistence\LaravelModel;

class Championship extends LaravelModel
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $championshipYear;

    /**
     * @var int
     */
    private $championshipMonth;
}

<?php

namespace App\Football\Models;

use App\ProjectElements\Persistence\LaravelModel;
use Illuminate\Database\Eloquent\Model;

class Team extends LaravelModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $country;

    /**
     * @var string
     */
    private $flag;

    /**
     * @var int
     */
    private $rank;

    /**
     * @var string
     */
    private $nationality;

}

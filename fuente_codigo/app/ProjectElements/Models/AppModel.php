<?php

namespace App\ProjectElements\Models;

abstract class AppModel extends LaravelModel
{
    public function __call($method, $arguments) {
        return parent::__call($method, $arguments);
    }
}
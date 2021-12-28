<?php

namespace App\ProjectInterfaces;

interface ManagerInterface
{
    public static function createEntity(
        array $data
    ) : object;
}
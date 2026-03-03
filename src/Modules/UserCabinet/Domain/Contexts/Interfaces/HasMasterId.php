<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

interface HasMasterId
{
    public function getMasterId(): int;
}
<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

interface HasModeUnitCount
{
    public function getModeUnitCount(): int;
}
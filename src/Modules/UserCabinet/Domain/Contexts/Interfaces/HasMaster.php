<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\UserCabinet\Domain\Entity\User;

interface HasMaster
{
    public function getMaster(): User;
}
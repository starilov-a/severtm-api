<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

use App\Modules\Common\Domain\Entity\User;

interface HasMaster
{
    public function getMaster(): User;
}
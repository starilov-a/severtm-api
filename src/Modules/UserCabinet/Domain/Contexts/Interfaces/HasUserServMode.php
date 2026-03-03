<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\UserCabinet\Domain\Entity\UserServMode;

interface HasUserServMode
{
    public function getUserServMode(): UserServMode;
}
<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;

interface HasMaster
{
    public function getMaster(): User;
}
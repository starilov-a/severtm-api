<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserServMode;

interface HasUserServMode
{
    public function getUserServMode(): UserServMode;
}
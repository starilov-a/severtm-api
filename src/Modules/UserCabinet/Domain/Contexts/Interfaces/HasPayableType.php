<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserPayableType;

interface HasPayableType
{
    public function getPayableType(): UserPayableType;
}
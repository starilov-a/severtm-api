<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\UserCabinet\Domain\Entity\FinPeriod;

interface HasFinPeriod
{
    public function getFinPeriod(): FinPeriod;
}
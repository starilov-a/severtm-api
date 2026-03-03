<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\UserCabinet\Domain\Entity\FinPeriod;

interface HasRefundFinPeriod
{
    public function getRefundFinPeriod(): FinPeriod;
}
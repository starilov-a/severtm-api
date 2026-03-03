<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\UserCabinet\Domain\Entity\FinPeriod;

interface HasRefundFinPeriodNullable
{
    public function getRefundFinPeriodNullable(): ?FinPeriod;
}
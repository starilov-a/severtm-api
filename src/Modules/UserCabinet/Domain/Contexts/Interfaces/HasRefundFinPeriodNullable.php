<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\FinPeriod;

interface HasRefundFinPeriodNullable
{
    public function getRefundFinPeriodNullable(): ?FinPeriod;
}
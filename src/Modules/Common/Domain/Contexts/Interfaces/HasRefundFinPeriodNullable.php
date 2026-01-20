<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

use App\Modules\Common\Domain\Entity\FinPeriod;

interface HasRefundFinPeriodNullable
{
    public function getRefundFinPeriodNullable(): ?FinPeriod;
}
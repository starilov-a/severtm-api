<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces;

use App\Modules\Common\Domain\Entity\FinPeriod;

interface HasRefundFinPeriodNullable
{
    public function getRefundFinPeriodNullable(): ?FinPeriod;
}
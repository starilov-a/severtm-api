<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

use App\Modules\Common\Domain\Entity\FinPeriod;

interface HasRefundFinPeriod
{
    public function getRefundFinPeriod(): FinPeriod;
}
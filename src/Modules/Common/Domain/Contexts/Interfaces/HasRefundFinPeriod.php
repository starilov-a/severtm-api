<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

use App\Modules\Common\Domain\Entity\FinPeriod;

interface HasRefundFinPeriod
{
    public function getRefundFinPeriod(): FinPeriod;
}
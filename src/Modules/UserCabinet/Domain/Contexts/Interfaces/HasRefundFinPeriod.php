<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Interfaces;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\FinPeriod;

interface HasRefundFinPeriod
{
    public function getRefundFinPeriod(): FinPeriod;
}
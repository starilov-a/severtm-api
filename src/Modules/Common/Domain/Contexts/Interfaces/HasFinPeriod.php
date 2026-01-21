<?php

namespace App\Modules\Common\Domain\Contexts\Interfaces;

use App\Modules\Common\Domain\Entity\FinPeriod;

interface HasFinPeriod
{
    public function getFinPeriod(): FinPeriod;
}
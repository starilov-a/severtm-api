<?php

namespace App\Modules\Common\Domain\Service\Rules\ContextInterfaces;

use App\Modules\Common\Domain\Entity\FinPeriod;

interface HasFinPeriod
{
    public function getFinPeriod(): FinPeriod;
}
<?php

namespace App\Modules\Common\Domain\Contexts\Definitions\FinPeriod;

use App\Modules\Common\Domain\Entity\FinPeriod;

class OnlyFinPeriod implements \App\Modules\Common\Domain\Contexts\Interfaces\HasFinPeriod
{
    public function __construct(
        protected FinPeriod $finPeriod,
    ) {}

    public function getFinPeriod(): FinPeriod
    {
        return $this->finPeriod;
    }
}
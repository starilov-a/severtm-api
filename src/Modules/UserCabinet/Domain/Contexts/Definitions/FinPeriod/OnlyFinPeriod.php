<?php

namespace App\Modules\UserCabinet\Domain\Contexts\Definitions\FinPeriod;

use App\Modules\UserCabinet\Domain\Entity\FinPeriod;

class OnlyFinPeriod implements \App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasFinPeriod
{
    public function __construct(
        protected FinPeriod $finPeriod,
    ) {}

    public function getFinPeriod(): FinPeriod
    {
        return $this->finPeriod;
    }
}
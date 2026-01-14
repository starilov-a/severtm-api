<?php

namespace App\Modules\Common\Domain\Service\Rules\Contexts;

use App\Modules\Common\Domain\Entity\FinPeriod;

class OnlyFinPeriod implements ContextInterfaces\HasFinPeriod
{
    public function __construct(
        protected FinPeriod $finPeriod,
    ) {}

    public function getFinPeriod(): FinPeriod
    {
        return $this->finPeriod;
    }
}
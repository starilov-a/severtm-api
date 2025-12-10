<?php

namespace App\Modules\Common\Domain\Service\Rules\FinPeriod;

use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasFinPeriod;

class IsCurrentFinPeriodContext implements HasFinPeriod
{
    public function __construct(
        protected FinPeriod $finPeriod
    ){}

    /**
     * @return FinPeriod
     */
    public function getFinPeriod(): FinPeriod
    {
        return $this->finPeriod;
    }
}
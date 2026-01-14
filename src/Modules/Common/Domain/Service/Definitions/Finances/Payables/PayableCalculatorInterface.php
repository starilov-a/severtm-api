<?php

namespace App\Modules\Common\Domain\Service\Definitions\Finances\Payables;

use App\Modules\Common\Domain\Entity\UserServMode;

interface PayableCalculatorInterface
{
    public function calculate(UserServMode $userServMode): CalculatedPayable;
}
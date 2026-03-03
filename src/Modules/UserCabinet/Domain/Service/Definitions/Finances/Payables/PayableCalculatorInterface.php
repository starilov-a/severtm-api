<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances\Payables;

use App\Modules\UserCabinet\Domain\Entity\UserServMode;

interface PayableCalculatorInterface
{
    public function calculate(UserServMode $userServMode): CalculatedPayable;
}
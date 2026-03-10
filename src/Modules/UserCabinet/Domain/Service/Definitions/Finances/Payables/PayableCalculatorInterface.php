<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances\Payables;

use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\UserServMode;

interface PayableCalculatorInterface
{
    public function calculate(UserServMode $userServMode): CalculatedPayable;
}
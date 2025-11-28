<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\Balance;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\BalanceRepository;
use App\Modules\Common\Domain\Repository\DebtRepository;
use App\Modules\Common\Domain\Repository\ReplenishmentRepository;
use App\Modules\Common\Domain\Repository\WriteOffRepository;
use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;

class UserPaymentsService
{
    /*
     * Активация автоплатежа
     * */
    public function enableUserAutopayment(User $user): bool
    {
        return false;
    }

    /*
     * Отключение автоплатежа
     * */
    public function disableUserAutopayment(User $user): bool
    {
        return false;
    }
}
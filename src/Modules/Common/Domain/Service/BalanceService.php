<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\Balance;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\BalanceRepository;

class BalanceService
{
    public function __construct(
        protected BalanceRepository $balanceRepo,
    ){}

    /*
     * Получение баланса
     * */
    public function getUserBalance(User $user): Balance
    {
        return $this->balanceRepo->find($user->getId());
    }
}
<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances;

use App\Modules\UserCabinet\Domain\Entity\Balance;
use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\BalanceRepositoryInterface;

class BalanceService
{
    public function __construct(
        protected BalanceRepositoryInterface $balanceRepo,
    ){}

    /*
     * Получение баланса
     * */
    public function getUserBalance(User $user): Balance
    {
        return $this->balanceRepo->find($user->getId());
    }
}
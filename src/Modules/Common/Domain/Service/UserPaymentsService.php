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
    protected BalanceRepository $balanceRepo;
    protected WriteOffRepository $writeOffRepo;
    protected ReplenishmentRepository $replenishmentRepo;
    protected DebtRepository $debtRepo;
    public function __construct(
        BalanceRepository $balanceRepo,
        WriteOffRepository $writeOffRepo,
        ReplenishmentRepository $replenishmentRepo,
        DebtRepository $debtRepo
    )
    {
        $this->balanceRepo = $balanceRepo;
        $this->writeOffRepo = $writeOffRepo;
        $this->replenishmentRepo = $replenishmentRepo;
        $this->debtRepo = $debtRepo;
    }

    /*
     * Получение баланса
     * */
    public function getUserBalance(User $user): Balance
    {
        return $this->balanceRepo->find($user->getId());
    }

    /*
     * Получение задолжности
     * */
    public function getUserDebt(User $user): float
    {
        return $this->debtRepo->sumByUser($user->getId());
    }

    /*
     * Получение списаний
     * */
    public function getUserWriteOffs(User $user, FilterDto $filter): array
    {
        return $this->writeOffRepo->findByUser($user->getId(), $filter);
    }

    /*
     * Пополнения пользователя
     * */
    public function getUserReplenishments(User $user, FilterDto $filter): array
    {
        return $this->replenishmentRepo->findByUser($user->getId(), $filter);
    }

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
<?php

namespace App\Modules\UserCabinet\Application\UseCase\Payment;

use App\Modules\UserCabinet\Domain\Repository\BalanceRepository;
use App\Modules\UserCabinet\Domain\Repository\DebtRepository;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\BalanceService;

class GetBalanceUseCase
{
    public function __construct(
        protected UserRepository $userRepo,
        protected BalanceService $balanceService,
        protected DebtRepository $debtRepo,
        protected BalanceRepository $balanceRepo,
    ) {}

    /*
     * Получение баланса
     * */
    public function handle(int $uid): array
    {
        $user = $this->userRepo->find($uid);
        $balance = $this->balanceRepo->find($user->getId());
        $debt = $this->debtRepo->sumByUser($user);

        return [
            'balance' => $balance->get() - $debt
        ];
    }
}

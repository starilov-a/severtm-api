<?php

namespace App\Modules\UserCabinet\Application\UseCase\Payment;

use App\Modules\UserCabinet\Domain\RepositoryInterface\BalanceRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\DebtRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\Definitions\Finances\BalanceService;

class GetBalanceUseCase
{
    public function __construct(
        protected UserRepositoryInterface $userRepo,
        protected BalanceService $balanceService,
        protected DebtRepositoryInterface $debtRepo,
        protected BalanceRepositoryInterface $balanceRepo,
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

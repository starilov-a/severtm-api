<?php

namespace App\Modules\UserCabinet\Application\UseCase\Payment;

use App\Modules\UserCabinet\Domain\Repository\BalanceRepository;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;

class GetBalanceUseCase
{
    protected function __construct(
        protected UserRepository $userRepo,
        protected BalanceRepository $balanceRepo
    ) {}
    public function handle()
    {
        $user = $this->userRepo->find($uid);
        $balance = $this->balanceService->getUserBalance($user);
        $debt = $this->debtService->getUserDebt($user);

        return [
            'balance' => $balance->get() - $debt
        ];
    }
}
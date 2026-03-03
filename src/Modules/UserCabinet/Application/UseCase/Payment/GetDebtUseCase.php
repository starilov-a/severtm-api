<?php

namespace App\Modules\UserCabinet\Application\UseCase\Payment;

use App\Modules\UserCabinet\Domain\Repository\DebtRepository;
use App\Modules\UserCabinet\Domain\Repository\UserRepository;

class GetDebtUseCase
{
    public function __construct(
        protected UserRepository $userRepo,
        protected DebtRepository $debtRepo
    ) {}
    /*
     * Получение задолжности
     * */
    public function handle($uid): array
    {
        $user = $this->userRepo->find($uid);
        $debt = $this->debtRepo->sumByUser($user);

        return [
            'debt' => $debt
        ];
    }
}
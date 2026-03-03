<?php

namespace App\Modules\UserCabinet\Application\UseCase\Payment;

use App\Modules\UserCabinet\Domain\RepositoryInterface\DebtRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserRepositoryInterface;

class GetDebtUseCase
{
    public function __construct(
        protected UserRepositoryInterface $userRepo,
        protected DebtRepositoryInterface $debtRepo
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

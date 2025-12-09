<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\DebtRepository;

class DebtService
{
    public function __construct(
        protected DebtRepository $debtRepo
    ){}
    /*
     * Получение задолжности
     * */
    public function getUserDebt(User $user): float
    {
        return $this->debtRepo->sumByUser($user->getId());
    }
}
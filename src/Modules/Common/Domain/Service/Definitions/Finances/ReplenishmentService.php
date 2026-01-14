<?php

namespace App\Modules\Common\Domain\Service\Definitions\Finances;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\ReplenishmentRepository;
use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;

class ReplenishmentService
{
    public function __construct(
        protected ReplenishmentRepository $replenishmentRepo
    ){}
    /*
     * Пополнения пользователя
     * */
    public function getUserReplenishments(User $user, FilterDto $filter): array
    {
        return $this->replenishmentRepo->findByUser($user->getId(), $filter);
    }
}
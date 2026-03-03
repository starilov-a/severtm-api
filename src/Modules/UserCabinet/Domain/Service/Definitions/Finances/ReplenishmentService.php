<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances;

use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ReplenishmentRepositoryInterface;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\FilterDto;

class ReplenishmentService
{
    public function __construct(
        protected ReplenishmentRepositoryInterface $replenishmentRepo
    ){}
    /*
     * Пополнения пользователя
     * */
    public function getUserReplenishments(User $user, FilterDto $filter): array
    {
        return $this->replenishmentRepo->findByUser($user->getId(), $filter);
    }
}

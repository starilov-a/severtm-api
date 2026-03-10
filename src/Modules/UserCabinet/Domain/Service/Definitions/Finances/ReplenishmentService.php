<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances;

use App\Modules\UserCabinet\Domain\Dto\Request\FilterDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ReplenishmentRepositoryInterface;

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

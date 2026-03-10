<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances;

use App\Modules\UserCabinet\Domain\Dto\Request\FilterDto;
use App\Modules\Common\Infrastructure\Persistence\Doctrine\Entity\Billing\User;
use App\Modules\UserCabinet\Domain\RepositoryInterface\ProdDiscountHistoryRepositoryInterface;

class ProdDiscountHistoryService
{
    public function __construct(
        protected ProdDiscountHistoryRepositoryInterface  $writeOffRepo,
    ){}

    /*
     * Получение истории списаний
     * */
    public function getUserWriteOffs(User $user, FilterDto $filter): array
    {
        return $this->writeOffRepo->findByUser($user->getId(), $filter);
    }
}
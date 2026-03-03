<?php

namespace App\Modules\UserCabinet\Domain\Service\Definitions\Finances;

use App\Modules\UserCabinet\Domain\Entity\User;
use App\Modules\UserCabinet\Domain\Repository\ProdDiscountHistoryRepository;
use App\Modules\UserCabinet\Domain\Service\Dto\Request\FilterDto;

class ProdDiscountHistoryService
{
    public function __construct(
        protected ProdDiscountHistoryRepository  $writeOffRepo,
    ){}

    /*
     * Получение истории списаний
     * */
    public function getUserWriteOffs(User $user, FilterDto $filter): array
    {
        return $this->writeOffRepo->findByUser($user->getId(), $filter);
    }
}
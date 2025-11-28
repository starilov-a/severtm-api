<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\FinPeriod;
use App\Modules\Common\Domain\Entity\ProdServMode;
use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Entity\UserServMode;
use App\Modules\Common\Domain\Entity\WriteOff;
use App\Modules\Common\Domain\Repository\WriteOffRepository;
use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;

class WriteOffService
{
    public function __construct(
        protected WriteOffRepository $writeOffRepo,
    ){}

    /*
     * Получение списаний
     * */
    public function getUserWriteOffs(User $user, FilterDto $filter): array
    {
        return $this->writeOffRepo->findByUser($user->getId(), $filter);
    }

    /*
     * Списание за режим
     * */
    public function makeWriteOffForMode(
        User $user,
        UserServMode $mode,
        FinPeriod $period,
    ): void
    {

    }
}
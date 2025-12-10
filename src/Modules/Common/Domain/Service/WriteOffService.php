<?php

namespace App\Modules\Common\Domain\Service;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\WriteOffRepository;
use App\Modules\Common\Domain\Service\Dto\Request\FilterDto;
use App\Modules\Common\Domain\Service\Dto\Request\TypedWriteOffDto;
use App\Modules\Common\Domain\Service\Rules\Chains\ShouldMakeWriteOffContext;
use App\Modules\Common\Domain\Service\Rules\Chains\ShouldMakeWriteOffRuleChain;

class WriteOffService
{
    public function __construct(
        protected WriteOffRepository $writeOffRepo,
        protected ShouldMakeWriteOffRuleChain $shouldMakeWriteOffRuleChain,
    ){}

    /*
     * Получение списаний
     * */
    public function getUserWriteOffs(User $user, FilterDto $filter): array
    {
        return $this->writeOffRepo->findByUser($user->getId(), $filter);
    }

    /*
     * Списание при добавлении режима
     * */
    public function makeWriteOffForAddingMode(TypedWriteOffDto $writeOffDto): void
    {
        // Проверки
        $contextForRule = new ShouldMakeWriteOffContext($user, $writeOffDto->getFinPeriod());
        if (!$this->shouldMakeWriteOffRuleChain->checkAll($contextForRule))
            return;




    }
}
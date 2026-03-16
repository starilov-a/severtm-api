<?php

namespace App\Modules\JurManagerCabinet\Domain\Rules\Definitions\Contract;

use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;
use App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces\HasContract;
use App\Modules\JurManagerCabinet\Domain\Entity\Reissue\ContractReissueStatus;
use App\Modules\JurManagerCabinet\Domain\RepositoryInterface\ContractReissueProcessRepositoryInterface;

class ReissueNotYetScheduledRule extends Rule
{

    public function __construct(
        protected ContractReissueProcessRepositoryInterface $processRepo,
    ) {}
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasContract))
            throw new \LogicException('Wrong context passed to ReissueNotYetScheduledRule');

        $alreadyScheduled = $this->processRepo->findScheduledByContract($context->getContract());

        //TODO: Получать дату переоформления и id нового договора
        if ($alreadyScheduled->status() === ContractReissueStatus::SCHEDULED)
            RuleResult::fail('Переоформление уже запланировано');

        return RuleResult::ok();
    }
}
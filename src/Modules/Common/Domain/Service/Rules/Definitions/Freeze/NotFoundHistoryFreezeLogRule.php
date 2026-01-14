<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Freeze;

use App\Modules\Common\Domain\Entity\AllHistoryKind;
use App\Modules\Common\Domain\Repository\AllHistoryKindRepository;
use App\Modules\Common\Domain\Repository\AllHistoryRepository;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

class NotFoundHistoryFreezeLogRule extends Rule
{
    public function __construct(
        protected AllHistoryRepository $allHistoryRepo,
        protected AllHistoryKindRepository $allHistoryKindRepo,
    ) {}
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to UserMustNotBeBlockedRule');

        $historyLog = $this->allHistoryRepo->findOneBy([
            'user' => $context->getUser(),
            'kind' => $this->allHistoryKindRepo->findOneBy(['strCode' => 'frozen'])
        ]);

        if (!$historyLog)
            return RuleResult::fail("Не существует записи о заморозке");

        return RuleResult::ok();
    }
}
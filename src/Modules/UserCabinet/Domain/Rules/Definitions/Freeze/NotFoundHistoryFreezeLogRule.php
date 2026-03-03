<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Freeze;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\Repository\AllHistoryKindRepository;
use App\Modules\UserCabinet\Domain\Repository\AllHistoryRepository;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;

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
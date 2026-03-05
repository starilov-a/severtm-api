<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Freeze;

use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\RepositoryInterface\AllHistoryKindRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\AllHistoryRepositoryInterface;

class NotFoundHistoryFreezeLogRule extends Rule
{
    public function __construct(
        protected AllHistoryRepositoryInterface $allHistoryRepo,
        protected AllHistoryKindRepositoryInterface $allHistoryKindRepo,
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
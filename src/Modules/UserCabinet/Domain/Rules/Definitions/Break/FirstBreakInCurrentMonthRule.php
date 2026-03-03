<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Break;

use App\Modules\Common\Rules\Results\RuleResult;
use App\Modules\Common\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\RepositoryInterface\CreditHistoryRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\FinPeriodRepositoryInterface;

class FirstBreakInCurrentMonthRule extends Rule
{
    public function __construct(
        protected FinPeriodRepositoryInterface       $finPeriodRepo,
        protected CreditHistoryRepositoryInterface   $creditHistoryRepo,
    ) {}
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to FirstBreakInCurrentMonthRule');

        if ($this->creditHistoryRepo->hasAnyForFinPeriodForUser($context->getUser(), $this->finPeriodRepo->getCurrent()))
            return RuleResult::fail('В этом месяце отсрочка уже была активирована!');

        return RuleResult::ok();
    }
}
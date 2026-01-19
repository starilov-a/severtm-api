<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Break;

use App\Modules\Common\Domain\Entity\User;
use App\Modules\Common\Domain\Repository\CreditHistoryRepository;
use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Service\BreakService;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

class FirstBreakInCurrentMonthRule extends Rule
{
    public function __construct(
        protected FinPeriodRepository       $finPeriodRepo,
        protected CreditHistoryRepository   $creditHistoryRepo,
    ) {}
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser)) throw new \LogicException('Wrong context passed to FirstBreakInCurrentMonthRule');

        if ($this->creditHistoryRepo->hasAnyForFinPeriodForUser($context->getUser(), $this->finPeriodRepo->getCurrent()))
            return RuleResult::fail('В этом месяце отсрочка уже была активирована!');

        return RuleResult::ok();
    }
}
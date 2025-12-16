<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains;

use App\Modules\Common\Domain\Service\Rules\Freeze\CurrentTariffMustAllowFreezeRule;
use App\Modules\Common\Domain\Service\Rules\Freeze\FreezeEligibilityByBalanceOrSeniorityOrFutureMonthRule;
use App\Modules\Common\Domain\Service\Rules\Freeze\FreezeOnlyOncePerMonthRule;
use App\Modules\Common\Domain\Service\Rules\Freeze\NoActiveMultiMonthModesInCurrentPeriodRule;
use App\Modules\Common\Domain\Service\Rules\Freeze\NoExistingNewFreezeTaskRule;
use App\Modules\Common\Domain\Service\Rules\Freeze\StartDateMustBeTodayOrFutureRule;
use App\Modules\Common\Domain\Service\Rules\RuleChain;
use App\Modules\Common\Domain\Service\Rules\User\UserMustNotBeBlockedRule;

class CreateFreezeTaskRuleChain extends RuleChain
{
    public function __construct(
        UserMustNotBeBlockedRule $userMustNotBeBlockedRule,
        StartDateMustBeTodayOrFutureRule $startDateMustBeTodayOrFutureRule,
        FreezeOnlyOncePerMonthRule $freezeOnlyOncePerMonthRule,
        NoExistingNewFreezeTaskRule $noExistingNewFreezeTaskRule,
        CurrentTariffMustAllowFreezeRule $currentTariffMustAllowFreezeRule,
        NoActiveMultiMonthModesInCurrentPeriodRule $noActiveMultiMonthModesInCurrentPeriodRule,
        FreezeEligibilityByBalanceOrSeniorityOrFutureMonthRule $freezeEligibilityRule,
    ) {
        $this->rules = [
            $userMustNotBeBlockedRule,
            $startDateMustBeTodayOrFutureRule,
            $freezeOnlyOncePerMonthRule,
            $noExistingNewFreezeTaskRule,
            $currentTariffMustAllowFreezeRule,
            $noActiveMultiMonthModesInCurrentPeriodRule,
            $freezeEligibilityRule,
        ];
    }
}

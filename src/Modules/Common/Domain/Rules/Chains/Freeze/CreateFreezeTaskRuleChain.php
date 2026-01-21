<?php

namespace App\Modules\Common\Domain\Rules\Chains\Freeze;

use App\Modules\Common\Domain\Rules\Definitions\Freeze\CurrentTariffMustAllowFreezeRule;
use App\Modules\Common\Domain\Rules\Definitions\Freeze\FreezeEligibilityByBalanceOrSeniorityOrFutureMonthRule;
use App\Modules\Common\Domain\Rules\Definitions\Freeze\FreezeOnlyOncePerMonthRule;
use App\Modules\Common\Domain\Rules\Definitions\Freeze\NoActiveMultiMonthModesInCurrentPeriodRule;
use App\Modules\Common\Domain\Rules\Definitions\Freeze\NoExistingNewFreezeTaskRule;
use App\Modules\Common\Domain\Rules\Definitions\Freeze\StartDateMustBeTodayOrFutureRule;
use App\Modules\Common\Domain\Rules\Definitions\User\UserIsNotFrozenRule;
use App\Modules\Common\Domain\Rules\Definitions\User\UserMustNotBeBlockedRule;
use App\Modules\Common\Domain\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class CreateFreezeTaskRuleChain extends RuleChain
{
    public function __construct(
        LoggerService $loggerService,

        UserIsNotFrozenRule $userIsNotFrozenRule,
        UserMustNotBeBlockedRule $userMustNotBeBlockedRule,
        StartDateMustBeTodayOrFutureRule $startDateMustBeTodayOrFutureRule,
        FreezeOnlyOncePerMonthRule $freezeOnlyOncePerMonthRule,
        NoExistingNewFreezeTaskRule $noExistingNewFreezeTaskRule,
        CurrentTariffMustAllowFreezeRule $currentTariffMustAllowFreezeRule,
        NoActiveMultiMonthModesInCurrentPeriodRule $noActiveMultiMonthModesInCurrentPeriodRule,
        FreezeEligibilityByBalanceOrSeniorityOrFutureMonthRule $freezeEligibilityRule,
    ) {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($userIsNotFrozenRule),
            new ChainRuleItem($userMustNotBeBlockedRule),
            new ChainRuleItem($startDateMustBeTodayOrFutureRule),
            new ChainRuleItem($freezeOnlyOncePerMonthRule),
            new ChainRuleItem($noExistingNewFreezeTaskRule),
            new ChainRuleItem($currentTariffMustAllowFreezeRule),
            new ChainRuleItem($noActiveMultiMonthModesInCurrentPeriodRule),
            new ChainRuleItem($freezeEligibilityRule)
        ];
    }
}

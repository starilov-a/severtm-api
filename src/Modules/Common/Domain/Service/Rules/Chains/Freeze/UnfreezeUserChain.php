<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains\Freeze;

use App\Modules\Common\Domain\Service\Rules\Definitions\Freeze\IsNotFrozenRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\Freeze\NotFoundHistoryFreezeLogRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\Semaphore\CloseMonthSemaphoreIsNotRunningRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\User\UserFreezingBeforeRule;
use App\Modules\Common\Domain\Service\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Service\Rules\Results\RuleMode;
use App\Modules\Common\Domain\Service\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class UnfreezeUserChain extends RuleChain
{
    public function __construct(
        LoggerService $loggerService,

        IsNotFrozenRule $isNotFrozenRule,
        CloseMonthSemaphoreIsNotRunningRule $closeMonthSemaphoreIsNotRunningRule,
        UserFreezingBeforeRule $userFreezingBeforeRule,
        NotFoundHistoryFreezeLogRule $notFoundHistoryFreezeLogRule
    ) {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($isNotFrozenRule),
            new ChainRuleItem($closeMonthSemaphoreIsNotRunningRule, RuleMode::HARD, 'HiddenImportantBusinessException'),
            new ChainRuleItem($userFreezingBeforeRule),
            new ChainRuleItem($notFoundHistoryFreezeLogRule),
        ];
    }
}
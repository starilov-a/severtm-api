<?php

namespace App\Modules\Common\Domain\Rules\Chains\Freeze;

use App\Modules\Common\Domain\Rules\Definitions\Freeze\NotFoundHistoryFreezeLogRule;
use App\Modules\Common\Domain\Rules\Definitions\Semaphore\CloseMonthSemaphoreIsNotRunningRule;
use App\Modules\Common\Domain\Rules\Definitions\User\UserFreezingBeforeRule;
use App\Modules\Common\Domain\Rules\Definitions\User\UserIsFrozenRule;
use App\Modules\Common\Domain\Rules\Definitions\User\UserIsNotFrozenRule;
use App\Modules\Common\Domain\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Rules\Results\RuleMode;
use App\Modules\Common\Domain\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class UnfreezeUserChain extends RuleChain
{
    public function __construct(
        LoggerService                       $loggerService,

        UserIsFrozenRule                    $isFrozenRule,
        CloseMonthSemaphoreIsNotRunningRule $closeMonthSemaphoreIsNotRunningRule,
        UserFreezingBeforeRule              $userFreezingBeforeRule,
        NotFoundHistoryFreezeLogRule        $notFoundHistoryFreezeLogRule
    ) {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($isFrozenRule),
            new ChainRuleItem($closeMonthSemaphoreIsNotRunningRule, RuleMode::HARD, 'HiddenImportantBusinessException'),
            new ChainRuleItem($userFreezingBeforeRule),
            new ChainRuleItem($notFoundHistoryFreezeLogRule),
        ];
    }
}
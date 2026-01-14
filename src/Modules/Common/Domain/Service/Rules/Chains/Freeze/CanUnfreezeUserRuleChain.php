<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains\Freeze;

use App\Modules\Common\Domain\Service\Rules\Definitions\User\UserIsFrozenRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\UserTask\IssetNewFreezeTaskRule;
use App\Modules\Common\Domain\Service\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Service\Rules\Results\RuleMode;
use App\Modules\Common\Domain\Service\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class CanUnfreezeUserRuleChain extends RuleChain
{
    public function __construct(
        LoggerService $loggerService,

        UserIsFrozenRule $userIsFrozenRule,
        IssetNewFreezeTaskRule $issetNewFreezeTaskRule,
    ) {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($userIsFrozenRule, RuleMode::SOFT),
            new ChainRuleItem($issetNewFreezeTaskRule, RuleMode::SOFT)
        ];
    }
}
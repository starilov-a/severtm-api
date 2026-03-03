<?php

namespace App\Modules\UserCabinet\Domain\Rules\Chains\Freeze;

use App\Modules\Common\Rules\Results\ChainRuleItem;
use App\Modules\Common\Rules\Results\RuleMode;
use App\Modules\Common\Rules\RuleChain;
use App\Modules\UserCabinet\Domain\Rules\Definitions\User\UserIsFrozenRule;
use App\Modules\UserCabinet\Domain\Rules\Definitions\UserTask\IssetNewFreezeTaskRule;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

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
<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains\Break;

use App\Modules\Common\Domain\Service\Rules\Definitions\Break\HasNoAvailableBreaksRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\User\UserIsFrozenRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\User\UserIsNotFrozenRule;
use App\Modules\Common\Domain\Service\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Service\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

/**
 * Можно ли брать отсрочку
 */
class CanGetBreakRuleChain extends RuleChain
{
    public function __construct(
        LoggerService                   $loggerService,

        UserIsNotFrozenRule             $userIsNotFrozenRule,
        HasNoAvailableBreaksRule        $hasNoAvailableBreaksRule
    ) {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($userIsNotFrozenRule),
            new ChainRuleItem($hasNoAvailableBreaksRule),
        ];
    }
}
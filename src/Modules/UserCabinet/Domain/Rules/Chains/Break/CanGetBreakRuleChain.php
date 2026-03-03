<?php

namespace App\Modules\UserCabinet\Domain\Rules\Chains\Break;

use App\Modules\UserCabinet\Domain\Rules\Definitions\Break\HasNoAvailableBreaksRule;
use App\Modules\UserCabinet\Domain\Rules\Definitions\User\UserHaveNotActiveBreakRule;
use App\Modules\UserCabinet\Domain\Rules\Definitions\User\UserIsNotFrozenRule;
use App\Modules\UserCabinet\Domain\Rules\Results\ChainRuleItem;
use App\Modules\UserCabinet\Domain\Rules\RuleChain;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

/**
 * Можно ли брать отсрочку
 */
class CanGetBreakRuleChain extends RuleChain
{
    public function __construct(
        LoggerService                   $loggerService,

        UserIsNotFrozenRule             $userIsNotFrozenRule,
        UserHaveNotActiveBreakRule      $userHaveNotActiveBreakRule,
        HasNoAvailableBreaksRule        $hasNoAvailableBreaksRule
    ) {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($userIsNotFrozenRule),
            new ChainRuleItem($userHaveNotActiveBreakRule),
            new ChainRuleItem($hasNoAvailableBreaksRule),
        ];
    }
}
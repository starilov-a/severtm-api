<?php

namespace App\Modules\Common\Domain\Rules\Chains\Break;

use App\Modules\Common\Domain\Rules\Definitions\Break\HasNoAvailableBreaksRule;
use App\Modules\Common\Domain\Rules\Definitions\User\UserIsNotFrozenRule;
use App\Modules\Common\Domain\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Rules\RuleChain;
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
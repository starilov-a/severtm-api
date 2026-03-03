<?php

namespace App\Modules\UserCabinet\Domain\Rules\Chains\Break;

use App\Modules\Common\Rules\Results\ChainRuleItem;
use App\Modules\Common\Rules\Results\RuleMode;
use App\Modules\Common\Rules\RuleChain;
use App\Modules\UserCabinet\Domain\Rules\Definitions\Break\FirstBreakInCurrentMonthRule;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

/**
 * Может ли клиент взять себе отсрочку
 */
class ClientCanGetBreakRuleChain extends RuleChain
{
    public function __construct(
        LoggerService                   $loggerService,

        FirstBreakInCurrentMonthRule    $firstBreakInCurrentMonthRule
    ) {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($firstBreakInCurrentMonthRule, RuleMode::SOFT),
        ];
    }
}
<?php

namespace App\Modules\UserCabinet\Domain\Rules\Chains\Break;

use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\UserCabinet\Domain\Rules\Definitions\Break\FirstBreakInCurrentMonthRule;
use App\Modules\UserCabinet\Domain\Rules\Results\ChainRuleItem;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleMode;
use App\Modules\UserCabinet\Domain\Rules\RuleChain;

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
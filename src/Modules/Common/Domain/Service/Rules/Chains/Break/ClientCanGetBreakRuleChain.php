<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains\Break;

use App\Modules\Common\Domain\Service\Rules\Definitions\Break\FirstBreakInCurrentMonthRule;
use App\Modules\Common\Domain\Service\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Service\Rules\Results\RuleMode;
use App\Modules\Common\Domain\Service\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

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
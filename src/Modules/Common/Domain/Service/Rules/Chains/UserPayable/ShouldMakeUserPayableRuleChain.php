<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains\UserPayable;

use App\Modules\Common\Domain\Service\Rules\Definitions\UserPayable\CreateUserPayableRule;
use App\Modules\Common\Domain\Service\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Service\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class ShouldMakeUserPayableRuleChain extends RuleChain
{
    public function __construct(
        LoggerService          $loggerService,
        CreateUserPayableRule  $writeOffsParamsRule,
    )
    {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($writeOffsParamsRule),
        ];
    }
}
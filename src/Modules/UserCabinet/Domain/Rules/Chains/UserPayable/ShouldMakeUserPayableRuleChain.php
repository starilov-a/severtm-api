<?php

namespace App\Modules\UserCabinet\Domain\Rules\Chains\UserPayable;

use App\Modules\Common\Domain\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;
use App\Modules\UserCabinet\Domain\Rules\Definitions\UserPayable\CreateUserPayableRule;

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
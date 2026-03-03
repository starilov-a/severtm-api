<?php

namespace App\Modules\UserCabinet\Domain\Rules\Chains\UserPayable;

use App\Modules\Common\Rules\Results\ChainRuleItem;
use App\Modules\Common\Rules\RuleChain;
use App\Modules\UserCabinet\Domain\Rules\Definitions\UserPayable\CreateUserPayableRule;
use App\Modules\UserCabinet\Infrastructure\Service\Logger\LoggerService;

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
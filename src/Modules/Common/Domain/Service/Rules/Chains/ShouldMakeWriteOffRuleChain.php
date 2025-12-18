<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains;

use App\Modules\Common\Domain\Service\Rules\Definitions\FinPeriod\IsCurrentFinPeriodRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\User\UserIsNotActivatedRule;
use App\Modules\Common\Domain\Service\Rules\Definitions\WriteOff\WriteOffsParamsRule;
use App\Modules\Common\Domain\Service\Rules\Results\ChainRuleItem;
use App\Modules\Common\Domain\Service\Rules\Results\RuleMode;
use App\Modules\Common\Domain\Service\Rules\RuleChain;
use App\Modules\Common\Infrastructure\Service\Logger\LoggerService;

class ShouldMakeWriteOffRuleChain extends RuleChain
{
    public function __construct(
        LoggerService $loggerService,
        UserIsNotActivatedRule $userIsNotActivatedRule,
        IsCurrentFinPeriodRule $isCurrentFinPeriodRule,
        WriteOffsParamsRule $writeOffsParamsRule,
    )
    {
        parent::__construct($loggerService);
        $this->items = [
            new ChainRuleItem($userIsNotActivatedRule, RuleMode::SOFT),
            new ChainRuleItem($isCurrentFinPeriodRule),
            new ChainRuleItem($writeOffsParamsRule),
        ];
    }
}
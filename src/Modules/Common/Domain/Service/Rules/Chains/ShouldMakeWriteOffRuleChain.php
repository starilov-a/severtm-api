<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains;

use App\Modules\Common\Domain\Service\Rules\FinPeriod\IsCurrentFinPeriodRule;
use App\Modules\Common\Domain\Service\Rules\RuleChain;
use App\Modules\Common\Domain\Service\Rules\User\UserIsNotActivatedRule;
use App\Modules\Common\Domain\Service\Rules\WriteOff\WriteOffsParamsRule;

class ShouldMakeWriteOffRuleChain extends RuleChain
{
    public function __construct(
        UserIsNotActivatedRule $userIsNotActivatedRule,
        IsCurrentFinPeriodRule $isCurrentFinPeriodRule,
        WriteOffsParamsRule $writeOffsParamsRule,
    )
    {
        $this->rules = [
            $userIsNotActivatedRule,
            $isCurrentFinPeriodRule,
            $writeOffsParamsRule,
        ];
    }
}
<?php

namespace App\Modules\Common\Domain\Service\Rules\Chains;

use App\Modules\Common\Domain\Service\Rules\FinPeriod\IsCurrentFinPeriodRule;
use App\Modules\Common\Domain\Service\Rules\RuleChain;
use App\Modules\Common\Domain\Service\Rules\User\UserIsNotActivatedRule;

class ShouldMakeWriteOffRuleChain extends RuleChain
{
    public function __construct(
        UserIsNotActivatedRule $userIsNotActivatedRule,
        IsCurrentFinPeriodRule $isCurrentFinPeriodRule
    )
    {
        $this->rules = [
            $userIsNotActivatedRule,
            $isCurrentFinPeriodRule
        ];
    }
}
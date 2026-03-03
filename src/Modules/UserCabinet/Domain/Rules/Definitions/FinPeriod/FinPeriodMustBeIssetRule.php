<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\FinPeriod;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasNullbleVar;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;

class FinPeriodMustBeIssetRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasNullbleVar)) throw new \LogicException('Wrong context passed to FinPeriodMustBeIssetRule');

        // Основная бизнес логика
        if ($context->getNullbleVar() === null)
            return RuleResult::fail('Не указан финансовый период');

        return RuleResult::ok();
    }
}
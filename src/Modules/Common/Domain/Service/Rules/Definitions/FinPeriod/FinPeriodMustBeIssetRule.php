<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\FinPeriod;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasNullbleVar;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

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
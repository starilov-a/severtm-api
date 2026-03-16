<?php

namespace App\Modules\JurManagerCabinet\Domain\Rules\Definitions\Contract;

use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;
use App\Modules\JurManagerCabinet\Domain\Contexts\Interfaces\HasReissueDate;

class CurrentOrNextFinPeriodRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasReissueDate))
            throw new \LogicException('Wrong context passed to CurrentOrNextFinPeriodRule');

        $startOfMonth = new \DateTimeImmutable('first day of this month midnight');

        if ($startOfMonth > $context->getReissueDate()) {
            return RuleResult::fail('Дата переоформления должна быть в текущем месяцем либо позднее');
        }

        return RuleResult::ok();
    }
}
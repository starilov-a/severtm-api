<?php

namespace App\Modules\Common\Domain\Rules\Definitions\Break;

use App\Modules\Common\Domain\Contexts\Interfaces\HasCountAvailableBreaks;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

class HasNoAvailableBreaksRule extends Rule
{
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasCountAvailableBreaks))
            throw new \LogicException('Wrong context passed to HasNoAvailableBreaksRule');

        if ($context->getCountAvailableBreaks() <= 0)
            return RuleResult::fail('Нет доступных отсрочек');

        return RuleResult::ok();
    }
}
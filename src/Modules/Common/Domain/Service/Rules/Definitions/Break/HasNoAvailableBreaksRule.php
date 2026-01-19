<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Break;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasCountAvailableBreaks;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

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
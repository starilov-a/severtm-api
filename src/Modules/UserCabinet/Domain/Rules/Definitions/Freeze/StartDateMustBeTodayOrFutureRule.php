<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Freeze;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasStartFreezeDate;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;

/**
 * Бизнес-правило:
 * нельзя поставить заморозку “задним числом”.
 */
class StartDateMustBeTodayOrFutureRule extends Rule
{
    /** @var HasWebAction & HasStartFreezeDate $context */
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasStartFreezeDate) || !($context instanceof HasWebAction))
            throw new \LogicException('Wrong context passed to StartDateMustBeTodayOrFutureRule');

        $now = new \DateTimeImmutable();
        $startDate = $context->getStartFreezeDate()->setTime(0, 0);

        if ($startDate < $now)
            return RuleResult::fail('Дата начала заморозки не может быть в прошлом');

        return RuleResult::ok();
    }
}

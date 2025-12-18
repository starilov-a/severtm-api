<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Freeze;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasStartFreezeDate;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasWebAction;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

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

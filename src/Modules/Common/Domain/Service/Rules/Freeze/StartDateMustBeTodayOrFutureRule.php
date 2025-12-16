<?php

namespace App\Modules\Common\Domain\Service\Rules\Freeze;

use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskContext;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

/**
 * Бизнес-правило:
 * нельзя поставить заморозку “задним числом”.
 */
class StartDateMustBeTodayOrFutureRule extends Rule
{
    public function check(object $context): bool
    {
        if (!$context instanceof CreateFreezeTaskContext) {
            throw new \LogicException('Wrong context passed to StartDateMustBeTodayOrFutureRule');
        }

        $now = $context->getNow()->setTime(0, 0);
        $startDate = \DateTimeImmutable::createFromInterface($context->getStartDate())->setTime(0, 0);

        if ($startDate < $now) {
            throw new ImportantBusinessException(
                $context->getUserId(),
                $context->getActionId(),
                'Дата начала заморозки не может быть в прошлом'
            );
        }

        return true;
    }
}

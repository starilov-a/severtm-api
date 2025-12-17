<?php

namespace App\Modules\Common\Domain\Service\Rules\Freeze;

use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskContext;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasActionId;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasStartFreezeDate;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasWebAction;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

/**
 * Бизнес-правило:
 * нельзя поставить заморозку “задним числом”.
 */
class StartDateMustBeTodayOrFutureRule extends Rule
{
    /** @var HasWebAction & HasStartFreezeDate $context */
    public function check(object $context): bool
    {
        if (!($context instanceof HasStartFreezeDate) || !($context instanceof HasWebAction))
            throw new \LogicException('Wrong context passed to StartDateMustBeTodayOrFutureRule');

        $now = new \DateTimeImmutable();
        $startDate = $context->getStartFreezeDate()->setTime(0, 0);

        if ($startDate < $now) {
            throw new ImportantBusinessException(
                $this->getMasterId(),
                $context->getWebAction()->getId(),
                'Дата начала заморозки не может быть в прошлом'
            );
        }

        return true;
    }
}

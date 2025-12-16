<?php

namespace App\Modules\Common\Domain\Service\Rules\Freeze;

use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskContext;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;
use App\Modules\Common\Domain\Repository\UserTaskRepository;

/**
 * Бизнес-правило:
 * если старт заморозки в текущем или прошлом месяце (start_time <= текущего месяца),
 * то нельзя, если уже была завершённая заморозка в этом месяце.
 *
 * Нюанс: это правило не применяется, если __start_time в будущем месяце (YYYYmm > текущего).
 * Тогда можно планировать, даже если в этом месяце уже замораживали.
 */
class FreezeOnlyOncePerMonthRule extends Rule
{
    public function __construct(
        private UserTaskRepository $userTaskRepository,
    ) {
    }

    public function check(object $context): bool
    {
        if (!$context instanceof CreateFreezeTaskContext) {
            throw new \LogicException('Wrong context passed to FreezeOnlyOncePerMonthRule');
        }

        $startDate = \DateTimeImmutable::createFromInterface($context->getStartDate());
        $now = $context->getNow();

        if ((int)$startDate->format('Ym') > (int)$now->format('Ym')) {
            return true;
        }

        $startOfMonth = $now->modify('first day of this month midnight');

        $alreadyFrozen = $this->userTaskRepository->hasTaskWithStateInPeriod(
            $context->getUser(),
            $context->getFreezeTaskType(),
            $context->getFinishedState(),
            $startOfMonth,
            $now
        );

        if ($alreadyFrozen) {
            throw new ImportantBusinessException(
                $context->getUserId(),
                $context->getActionId(),
                'Заморозка доступна не чаще одного раза в месяц'
            );
        }

        return true;
    }
}

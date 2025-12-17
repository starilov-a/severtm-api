<?php

namespace App\Modules\Common\Domain\Service\Rules\Freeze;

use App\Modules\Common\Domain\Entity\UserTaskState;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskContext;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasStartFreezeDate;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasWebAction;
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
        protected UserTaskRepository $userTaskRepo,
        protected UserTaskStateRepository $taskStateRepo,
    ) {}

    /** @var HasWebAction & HasStartFreezeDate & HasUser $context */
    public function check(object $context): bool
    {
        if (
            !($context instanceof HasWebAction) ||
            !($context instanceof HasStartFreezeDate) ||
            !($context instanceof HasUser)
        ) throw new \LogicException('Wrong context passed to FreezeOnlyOncePerMonthRule');


        $startDate = $context->getStartFreezeDate()->setTime(0, 0);
        $now = new \DateTimeImmutable();

        //start_date в будущих месяцах
        if ((int)$startDate->format('Ym') > (int)$now->format('Ym'))
            return true;

        $alreadyFrozen = $this->userTaskRepo->hasTaskWithStateInPeriod(
            $context->getUser(),
            $this->taskStateRepo->findOneBy(['str_code' => 'new']),
            $this->taskStateRepo->findOneBy(['str_code' => 'finished']),
            new \DateTimeImmutable('first day of this month'),
            $now
        );

        if ($alreadyFrozen) {
            throw new ImportantBusinessException(
                $this->getMasterId(),
                $context->getWebAction()->getId(),
                'Заморозка доступна не чаще одного раза в месяц'
            );
        }

        return true;
    }
}

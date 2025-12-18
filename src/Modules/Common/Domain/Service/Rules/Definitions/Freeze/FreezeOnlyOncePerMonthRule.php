<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Freeze;

use App\Modules\Common\Domain\Repository\UserTaskRepository;
use App\Modules\Common\Domain\Repository\UserTaskStateRepository;
use App\Modules\Common\Domain\Repository\UserTaskTypeRepository;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasStartFreezeDate;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasWebAction;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

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
        protected UserTaskTypeRepository $taskTypeRepo,
    ) {}

    /** @var HasWebAction & HasStartFreezeDate & HasUser $context */
    public function check(object $context): RuleResult
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
            return RuleResult::ok();

        $alreadyFrozen = $this->userTaskRepo->hasTaskWithStateInPeriod(
            $context->getUser(),
            $this->taskTypeRepo->findOneBy(['code' => 'freeze']),
            $this->taskStateRepo->findOneBy(['code' => 'finished']),
            new \DateTimeImmutable('first day of this month'),
            $now
        );

        if ($alreadyFrozen)
            return RuleResult::fail('Заморозка доступна не чаще одного раза в месяц');

        return RuleResult::ok();
    }
}

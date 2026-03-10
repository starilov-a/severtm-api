<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Freeze;

use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasStartFreezeDate;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskStateRepositoryInterface;
use App\Modules\UserCabinet\Domain\RepositoryInterface\UserTaskTypeRepositoryInterface;
use App\Modules\UserCabinet\Domain\Rules\Results\RuleResult;
use App\Modules\UserCabinet\Domain\Rules\Rule;

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
        protected UserTaskRepositoryInterface $userTaskRepo,
        protected UserTaskStateRepositoryInterface $taskStateRepo,
        protected UserTaskTypeRepositoryInterface $taskTypeRepo,
    ) {}

    /** @var HasWebAction & HasStartFreezeDate & HasUser $context */
    public function check(object $context = null): RuleResult
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

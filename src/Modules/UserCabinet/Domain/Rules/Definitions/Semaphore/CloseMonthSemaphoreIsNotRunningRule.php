<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Semaphore;

use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;
use App\Modules\UserCabinet\Domain\RepositoryInterface\SemaphoreRepositoryInterface;

class CloseMonthSemaphoreIsNotRunningRule extends Rule
{

    public function __construct(
        protected SemaphoreRepositoryInterface $semaphoreRepo,
    ) {}
    public function check(object $context = null): RuleResult
    {
        $semaphores = $this->semaphoreRepo->findBy([
            'isRunning' => 1,
            'procedureName' => ['__month_close', '**stopper_for__all_uid_invoices__for_manual_use_do_not_delete**']
        ]);

        if (!empty($semaphores))
            return RuleResult::fail('Запущена блокировка закрытия месяца');

        return RuleResult::ok();
    }
}
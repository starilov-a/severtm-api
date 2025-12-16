<?php

namespace App\Modules\Common\Domain\Service\Rules\Freeze;

use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskContext;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

/**
 * Бизнес-правило:
 * То есть задача создаётся, если выполняется хотя бы одно:
 *
 * Баланс >= стоимость “базового” тарифа
 * → у клиента достаточно средств, чтобы не злоупотреблять freeze “на нуле”.
 *
 * Клиент зарегистрирован НЕ в текущем месяце (now YYYYmm > reg_date YYYYmm)
 * → “стаж” клиента минимум месяц. Новеньким в текущем месяце freeze нельзя (если ещё и баланс ниже порога и не план на будущий месяц).
 *
 * Start_time в будущем месяце (start_time YYYYmm > текущего)
 * → можно планировать freeze заранее на следующий месяц даже если сейчас баланс низкий и клиент новый.
 *
 *
 * Смысл: анти-абуз/анти-фрод + бизнес-политика:
 * новые клиенты с низким балансом не должны сразу “замораживаться” в текущем месяце,
 * но “старым” или с нормальным балансом — можно,
 * либо можно заранее планировать на будущий месяц.
 */
class FreezeEligibilityByBalanceOrSeniorityOrFutureMonthRule extends Rule
{
    public function check(object $context): bool
    {
        if (!$context instanceof CreateFreezeTaskContext) {
            throw new \LogicException('Wrong context passed to FreezeEligibilityByBalanceOrSeniorityOrFutureMonthRule');
        }

        $defaultTariffCost = $context->getDefaultTariffCost();
        $hasEnoughBalance = $defaultTariffCost <= 0 || $context->getUserBill() >= $defaultTariffCost;

        $nowMonth = (int)$context->getNow()->format('Ym');
        $startMonth = (int)$context->getStartDate()->format('Ym');
        $startsInFutureMonth = $startMonth > $nowMonth;

        $registrationDate = $context->getRegistrationDate();
        $hasSeniority = true;
        if (null !== $registrationDate) {
            $hasSeniority = (int)$registrationDate->format('Ym') < $nowMonth;
        }

        if (!$hasEnoughBalance && !$hasSeniority && !$startsInFutureMonth) {
            $message = sprintf(
                'Недостаточно оснований для заморозки: баланс %.2f руб., требуется не меньше %.2f руб. либо подключение > 1 месяца',
                $context->getUserBill(),
                max($defaultTariffCost, 0)
            );

            throw new ImportantBusinessException(
                $context->getUserId(),
                $context->getActionId(),
                $message
            );
        }

        return true;
    }
}

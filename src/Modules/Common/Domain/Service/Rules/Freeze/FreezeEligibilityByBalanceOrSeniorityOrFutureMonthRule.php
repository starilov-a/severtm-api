<?php

namespace App\Modules\Common\Domain\Service\Rules\Freeze;

use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskContext;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasStartFreezeDate;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasWebAction;
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
 * → “стаж” клиента минимум месяц. Новеньким в текущем месяце freeze нельзя
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
        /** @var HasWebAction & HasStartFreezeDate & HasUser $context */
        if (
            !($context instanceof HasWebAction) ||
            !($context instanceof HasStartFreezeDate) ||
            !($context instanceof HasUser)
        ) throw new \LogicException('Wrong context passed to FreezeEligibilityByBalanceOrSeniorityOrFutureMonthRule');

        $now = new \DateTimeImmutable();
        $cost = (float)$context->getUser()->getCurrentTariff()->getPrice();
        $balance = (int)$context->getUser()->getBill();

        //1. Имеем достаточный баланс
        $hasEnoughBalance = $cost <= 0 || $balance >= $cost;

        //2. регистрация не в этом месяце
        $registrationDate = $context->getUser()->getRegDate();
        $isNewClient = !(abs($registrationDate->getTimestamp() - $now->getTimestamp()) > 30 * 24);  // Меньше 30 дней

        if (!$hasEnoughBalance && $isNewClient) {
            throw new ImportantBusinessException(
                $this->getMasterId(),
                $context->getWebAction()->getId(),
                sprintf(
                    'Недостаточно оснований для заморозки: баланс %.2f руб., требуется не меньше %.2f руб. либо подключение < 1 месяца',
                    $balance,
                    max($cost, 0)
                )
            );
        }

        return true;
    }
}

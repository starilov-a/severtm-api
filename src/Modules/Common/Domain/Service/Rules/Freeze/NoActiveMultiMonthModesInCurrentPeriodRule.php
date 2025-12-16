<?php

namespace App\Modules\Common\Domain\Service\Rules\Freeze;

use App\Modules\Common\Domain\Repository\UserServModeRepository;
use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskContext;
use App\Modules\Common\Domain\Service\Rules\Rule;
use App\Modules\Common\Infrastructure\Exception\ImportantBusinessException;

/**
 * Бизнес-правило:
 * если у клиента в текущем фин-периоде есть активный режим с periods > 1 (действует больше 1 месяца),
 * то freeze запрещён — иначе можно сломать экономику/учёт предоплаты, сроки действия и т.п.
 */
class NoActiveMultiMonthModesInCurrentPeriodRule extends Rule
{
    public function __construct(
        private UserServModeRepository $userServModeRepository,
    ) {
    }

    public function check(object $context): bool
    {
        if (!$context instanceof CreateFreezeTaskContext) {
            throw new \LogicException('Wrong context passed to NoActiveMultiMonthModesInCurrentPeriodRule');
        }

        $finPeriodId = $context->getCurrentFinPeriod()->getId();
        $hasMultiMonthModes = $this->userServModeRepository->hasActiveMultiPeriodModes(
            $context->getUserId(),
            $finPeriodId
        );

        if ($hasMultiMonthModes) {
            throw new ImportantBusinessException(
                $context->getUserId(),
                $context->getActionId(),
                'Заморозка недоступна при активных предоплаченных услугах'
            );
        }

        return true;
    }
}

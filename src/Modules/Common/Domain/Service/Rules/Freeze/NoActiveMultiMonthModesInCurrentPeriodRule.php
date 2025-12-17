<?php

namespace App\Modules\Common\Domain\Service\Rules\Freeze;

use App\Modules\Common\Domain\Repository\FinPeriodRepository;
use App\Modules\Common\Domain\Repository\UserServModeRepository;
use App\Modules\Common\Domain\Service\Rules\Chains\CreateFreezeTaskContext;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasStartFreezeDate;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\ContextInterfaces\HasWebAction;
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
        protected UserServModeRepository $userServModeRepo,
        protected FinPeriodRepository $finPeriodRepo,
    ) {}

    /** @var HasWebAction & HasUser $context */
    public function check(object $context): bool
    {
        if (!($context instanceof HasUser) || !($context instanceof HasWebAction))
            throw new \LogicException('Wrong context passed to NoActiveMultiMonthModesInCurrentPeriodRule');

        $hasMultiMonthModes = $this->userServModeRepo->hasActiveMultiPeriodModes(
            $context->getUser(),
            $this->finPeriodRepo->getCurrent(),
        );

        if ($hasMultiMonthModes) {
            throw new ImportantBusinessException(
                $this->getMasterId(),
                $context->getWebAction()->getId(),
                'Заморозка недоступна при активных предоплаченных услугах'
            );
        }

        return true;
    }
}

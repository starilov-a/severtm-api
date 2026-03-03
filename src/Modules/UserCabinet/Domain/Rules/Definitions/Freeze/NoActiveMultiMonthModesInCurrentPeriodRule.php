<?php

namespace App\Modules\UserCabinet\Domain\Rules\Definitions\Freeze;

use App\Modules\Common\Rules\Results\RuleResult;
use App\Modules\Common\Rules\Rule;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasUser;
use App\Modules\UserCabinet\Domain\Contexts\Interfaces\HasWebAction;
use App\Modules\UserCabinet\Domain\Repository\FinPeriodRepository;
use App\Modules\UserCabinet\Domain\Repository\UserServModeRepository;

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
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser) || !($context instanceof HasWebAction))
            throw new \LogicException('Wrong context passed to NoActiveMultiMonthModesInCurrentPeriodRule');

        $hasMultiMonthModes = $this->userServModeRepo->hasActiveMultiPeriodModes(
            $context->getUser(),
            $this->finPeriodRepo->getCurrent(),
        );

        if ($hasMultiMonthModes)
            return RuleResult::fail('Заморозка недоступна при активных предоплаченных услугах');

        return RuleResult::ok();
    }
}

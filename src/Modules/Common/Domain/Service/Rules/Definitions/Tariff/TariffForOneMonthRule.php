<?php

namespace App\Modules\Common\Domain\Service\Rules\Definitions\Tariff;

use App\Modules\Common\Domain\Service\Rules\Contexts\ContextInterfaces\HasUser;
use App\Modules\Common\Domain\Service\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Service\Rules\Rule;

class TariffForOneMonthRule extends Rule
{
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasUser))
            throw new \LogicException('Wrong context passed to UserIsNotFrozenRule');

        if ($context->getUser()->getCurrentTariff()->getTariffPeriod() > 1)
            return RuleResult::fail('Период пользовательского тарифа более 1 месяца');

        return RuleResult::ok();
    }
}
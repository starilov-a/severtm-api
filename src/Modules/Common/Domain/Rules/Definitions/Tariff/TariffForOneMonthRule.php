<?php

namespace App\Modules\Common\Domain\Rules\Definitions\Tariff;

use App\Modules\Common\Domain\Contexts\Interfaces\HasUser;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

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
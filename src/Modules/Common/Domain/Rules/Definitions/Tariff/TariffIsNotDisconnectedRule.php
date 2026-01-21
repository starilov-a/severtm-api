<?php

namespace App\Modules\Common\Domain\Rules\Definitions\Tariff;

use App\Modules\Common\Domain\Contexts\Interfaces\HasTariff;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

class TariffIsNotDisconnectedRule extends Rule
{
    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasTariff))
            throw new \LogicException('Wrong context passed to TariffIsNotDisconnectedRule');

        if ($context->getTariff()->isDisconnected())
            return RuleResult::fail('Тариф является "Отключен от сети"');

        return RuleResult::ok();
    }
}
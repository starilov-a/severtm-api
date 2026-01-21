<?php

namespace App\Modules\Common\Domain\Rules\Definitions\Tariff;

use App\Modules\Common\Domain\Contexts\Interfaces\HasTariff;
use App\Modules\Common\Domain\Rules\Results\RuleResult;
use App\Modules\Common\Domain\Rules\Rule;

class CanBeChangedByClientRule extends Rule
{

    public function check(object $context = null): RuleResult
    {
        if (!($context instanceof HasTariff))
            throw new \LogicException('Wrong context passed to IssetRentProdModeNowForDisconnectRule');

        if ($context->getTariff()->canBeChangedByClient())
            return RuleResult::fail('Тариф не доступен для смены клиентом');

        return RuleResult::ok();
    }
}